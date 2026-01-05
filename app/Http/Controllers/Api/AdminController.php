<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complain;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Reply to a complaint
     */
    public function replyToComplaint(Request $request, $complaintId)
    {
        $user = Auth::user();

        // Check if user is admin or gestionnaire
        if (!$user->hasRole(['admin', 'gestionnaire'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only admins and gestionnaires can reply to complaints.',
            ], 403);
        }

        $complaint = Complain::findOrFail($complaintId);

        // Check if user can reply to this complaint
        $canReply = false;
        if ($user->hasRole('admin') && $complaint->target_role === 'admin') {
            $canReply = true;
        } elseif ($user->hasRole('gestionnaire') && $complaint->target_role === 'gestionnaire') {
            $canReply = true;
        }

        if (!$canReply) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot reply to this complaint.',
            ], 403);
        }

        $request->validate([
            'response' => 'required|string|max:5000',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            $uploadedImages = $request->file('images');
            if (count($uploadedImages) > 5) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can upload maximum 5 images.',
                ], 400);
            }

            foreach ($uploadedImages as $image) {
                $images[] = $image->store('complaints/responses', 'public');
            }
        }

        // Update complaint with response
        $updateData = [
            'status' => 'RESOLVED',
            'responded_at' => now(),
        ];

        if ($user->hasRole('admin')) {
            $updateData['admin_response'] = $request->response;
            if (!empty($images)) {
                $updateData['admin_response_images'] = $images;
            }
        } elseif ($user->hasRole('gestionnaire')) {
            $updateData['gestionnaire_response'] = $request->response;
            if (!empty($images)) {
                $updateData['gestionnaire_response_images'] = $images;
            }
        }

        $complaint->update($updateData);

        // Send notification to the agent who created the complaint
        $agent = $complaint->agentProfile;
        if ($agent && $agent->user) {
            $responderRole = $user->hasRole('admin') ? 'Admin' : 'Gestionnaire';

            NotificationService::send(
                $agent->user,
                'Response to your ' . strtolower($complaint->type),
                'Your ' . strtolower($complaint->type) . ' has been responded to by ' . $responderRole,
                [
                    'type' => 'complaint_response',
                    'complaint_id' => $complaint->id,
                    'responder_role' => strtolower($responderRole),
                ]
            );
        }

        // Reload complaint with relationships for response
        $complaint->load(['admin', 'gestionnaire', 'targetUser', 'sender']);

        $responseImages = [];
        $responseField = $user->hasRole('admin') ? 'admin_response_images' : 'gestionnaire_response_images';
        if ($complaint->$responseField && is_array($complaint->$responseField)) {
            foreach ($complaint->$responseField as $image) {
                if ($image) {
                    $cleanPath = ltrim($image, '/');
                    $responseImages[] = '/api/media/' . $cleanPath;
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Reply sent successfully',
            'complaint' => [
                'id' => $complaint->id,
                'status' => $complaint->status,
                'responded_at' => $complaint->responded_at,
                'admin_response' => $complaint->admin_response,
                'gestionnaire_response' => $complaint->gestionnaire_response,
                'admin_response_images' => $responseImages,
                'gestionnaire_response_images' => $responseImages,
                'updated_at' => $complaint->updated_at,
            ],
        ]);
    }
}
