<x-layouts.app :title="__('Live Stream - ' . $cctv->name)">
    <div class="page-wrapper">
        <div class="max-w-screen-xl mx-auto px-6 py-6 page-content-area">
            <div class="flex items-center justify-between gap-4 mb-6 live-stream-header">
                <h1 class="text-3xl md:text-4xl font-extrabold text-zinc-800 dark:text-white live-stream-title">
                    Live Stream - {{ $cctv->name }}
                </h1>
                <div class="flex items-center gap-2">
                    <a href="{{ url()->previous() }}" class="btn btn-primary glow live-stream-back-btn">Kembali</a>
                </div>
            </div>

            <!-- MAIN LIVE STREAM DISPLAY -->
            <div style="display: flex; justify-content: center; align-items: center; min-height: 800px; padding: 2rem;" class="live-stream-container">
                <div id="stream-container" style="width: 100%; max-width: 1152px; background-color: #000; border-radius: 0.75rem; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); cursor: pointer;" class="live-stream-player">
                    <!-- Video player area -->
                    @if($hlsUrl)
                        <div style="position: relative; padding-top: 56.25%;">
                            <video
                                id="live-stream-player"
                                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain;"
                                autoplay
                                playsinline
                                controls>
                                <source src="{{ $hlsUrl }}" type="application/x-mpegURL">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    @else
                        <!-- No stream display -->
                        <div style="position: relative; padding-top: 56.25%; background-color: #111827;">
                            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                <div style="text-align: center; color: #fff;">
                                    <div style="width: 384px; height: 384px; background-color: #dc2626; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;" class="live-stream-no-stream">
                                        <i class="bx bx-video-off" style="font-size: 9rem;"></i>
                                    </div>
                                    <h2 style="font-size: 1.5rem; font-weight: 700; color: #d1d5db;">No Stream Available</h2>
                                    <p style="font-size: 1.125rem; color: #6b7280; margin-top: 0.5rem;">Stream will start automatically</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Live Stream Responsive -->
    <style>
        @media (max-width: 768px) {
            .live-stream-container {
                padding: 1rem !important;
                min-height: 60vh !important;
            }
            .live-stream-header {
                flex-direction: column !important;
                gap: 1rem !important;
                align-items: stretch !important;
            }
            .live-stream-header h1 {
                font-size: 1.875rem !important;
                text-align: center !important;
            }
            .live-stream-header .btn {
                width: 100% !important;
                text-align: center !important;
            }
            .live-stream-player {
                border-radius: 0.5rem !important;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1) !important;
            }
            .live-stream-no-stream {
                width: 200px !important;
                height: 200px !important;
            }
            .live-stream-no-stream i {
                font-size: 4rem !important;
            }
            .live-stream-no-stream h2 {
                font-size: 1.125rem !important;
            }
            .live-stream-no-stream p {
                font-size: 0.875rem !important;
            }
        }
        @media (max-width: 480px) {
            .live-stream-container {
                padding: 0.75rem !important;
                min-height: 50vh !important;
            }
            .live-stream-header {
                padding: 1rem !important;
                margin-bottom: 1rem !important;
            }
            .live-stream-header h1 {
                font-size: 1.5rem !important;
            }
            .live-stream-player {
                border-radius: 0.375rem !important;
            }
            .live-stream-no-stream {
                width: 150px !important;
                height: 150px !important;
            }
            .live-stream-no-stream i {
                font-size: 3rem !important;
            }
            .live-stream-no-stream h2 {
                font-size: 1rem !important;
            }
            .live-stream-no-stream p {
                font-size: 0.75rem !important;
            }
        }

        /* Tablet specific styles */
        @media (min-width: 769px) and (max-width: 1024px) {
            .page-content-area {
                padding: 1.5rem !important;
            }
            .live-stream-header h1 {
                font-size: 2.5rem !important;
            }
            #stream-container {
                max-width: 90% !important;
                min-height: 650px !important;
            }
        }

        /* Desktop specific styles */
        @media (min-width: 1025px) {
            .page-content-area {
                padding: 2rem !important;
            }
            #stream-container {
                max-width: 1152px !important;
                min-height: 800px !important;
            }
        }

        /* Large desktop */
        @media (min-width: 1440px) {
            .page-content-area {
                padding: 2.5rem !important;
                max-width: 1400px !important;
                margin: 0 auto !important;
            }
            #stream-container {
                max-width: 1280px !important;
                min-height: 850px !important;
            }
        }

        /* Ensure video is responsive */
        #live-stream-player {
            width: 100% !important;
            height: 100% !important;
        }

        /* Fix for aspect ratio on all devices */
        @media (max-aspect-ratio: 1/1) {
            #stream-container {
                min-height: 60vh !important;
            }
        }
    </style>

    <!-- Add JavaScript for click functionality -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const container = document.getElementById("stream-container");
        const video = document.getElementById("live-stream-player");

        if (container) {
            // Add click event to toggle fullscreen
            container.addEventListener("click", function() {
                // Check if already in fullscreen
                if (document.fullscreenElement) {
                    // Exit fullscreen
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    } else if (document.webkitExitFullscreen) {
                        document.webkitExitFullscreen();
                    } else if (document.msExitFullscreen) {
                        document.msExitFullscreen();
                    }
                } else {
                    // Enter fullscreen
                    if (container.requestFullscreen) {
                        container.requestFullscreen();
                    } else if (container.webkitRequestFullscreen) {
                        container.webkitRequestFullscreen();
                    } else if (container.msRequestFullscreen) {
                        container.msRequestFullscreen();
                    }
                }
            });
        }

        // Handle ESC key to exit fullscreen
        document.addEventListener("keydown", function(e) {
            if (e.key === "Escape") {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            }
        });
    });
    </script>
</x-layouts.app>
