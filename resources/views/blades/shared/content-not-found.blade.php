<x-allthepages-layout :pageTitle="$pageTitle ?? 'Content Not Found'">
    <div style="padding: 2rem; max-width: 800px; margin: 0 auto;">
        <div style="background-color: #F3EBDD; border: 3px solid #193948; border-radius: 0.75rem; padding: 3rem; text-align: center;">
            <div style="font-size: 4rem; margin-bottom: 1.5rem; opacity: 0.7;">⚠️</div>
            <h1 style="color: #193948; font-size: 1.75rem; font-weight: 700; margin-bottom: 1rem;">
                {{ $title ?? 'Content Not Found' }}
            </h1>
            <p style="color: #36454f; font-size: 1rem; line-height: 1.6; margin-bottom: 2rem;">
                {{ $message ?? 'Sorry, the content you are looking for does not exist or has been deleted.' }}
            </p>
            <a href="{{ $backUrl ?? url()->previous() }}" style="display: inline-block; background-color: #193948; color: #F3EBDD; padding: 0.75rem 1.5rem; border-radius: 0.5rem; text-decoration: none; font-weight: 600; transition: all 0.3s;">
                Go Back
            </a>
        </div>
    </div>
</x-allthepages-layout>

