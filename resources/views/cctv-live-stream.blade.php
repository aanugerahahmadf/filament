<x-layouts.app :title="__('Live Stream - ' . $cctv->name)">
    <div class="page-wrapper">
        <div class="max-w-screen-xl mx-auto px-6 py-6 page-content-area">
            <div class="flex items-center justify-between gap-4 mb-6 live-stream-header">
                <div class="live-stream-title-container">
                    <h1 class="text-2xl md:text-3xl font-extrabold text-zinc-800 dark:text-white live-stream-subtitle">
                        Live Stream
                    </h1>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-zinc-800 dark:text-white live-stream-main-title">
                        {{ $cctv->name }}
                    </h1>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ url()->previous() }}" class="btn btn-primary glow live-stream-back-btn">Kembali</a>
                </div>
            </div>

            <!-- MAIN LIVE STREAM DISPLAY -->
            <div class="live-stream-container" style="width: 100%; height: auto;">
                <div id="stream-container" style="width: 100%; background-color: transparent; border-radius: 0;" class="live-stream-player">
                    <!-- Video player area -->
                    @if($hlsUrl)
                        <div style="position: relative; width: 100%; height: 0; padding-bottom: 56.25%;">
                            <video
                                id="live-stream-player"
                                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain; background: transparent;"
                                autoplay
                                playsinline
                                controls>
                                <source src="{{ $hlsUrl }}" type="application/x-mpegURL">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    @else
                        <!-- No stream display -->
                        <div style="position: relative; width: 100%; height: 0; padding-bottom: 56.25%;">
                            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background-color: #000;">
                                <div style="text-align: center; color: #fff;">
                                    <div style="width: 100px; height: 100px; background-color: transparent; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                                        <i class="bx bx-video-off" style="font-size: 3rem; color: #dc2626;"></i>
                                    </div>
                                    <h2 style="font-size: 1.25rem; font-weight: 600; color: #d1d5db;">No Stream Available</h2>
                                    <p style="font-size: 1rem; color: #6b7280; margin-top: 0.5rem;">Stream will start automatically</p>
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
        /* Base styles for all devices */
        .live-stream-container {
            width: 100%;
            margin: 0 auto;
            padding: 0;
        }

        .live-stream-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
            position: relative;
            min-height: 60px; /* Ensure space for the button */
        }

        .live-stream-title-container {
            flex: 1;
            min-width: 250px;
            padding-right: 100px; /* Space for the button */
        }

        .live-stream-subtitle {
            margin: 0 0 0.25rem 0;
            line-height: 1.2;
        }

        .live-stream-main-title {
            margin: 0;
            line-height: 1.2;
        }

        .live-stream-back-btn {
            white-space: nowrap;
            position: absolute;
            top: 0;
            right: 0;
            z-index: 10;
        }

        #stream-container {
            width: 100%;
            max-width: 100%;
            background-color: transparent;
            border-radius: 0;
            overflow: hidden;
        }

        /* Desktop styles */
        @media (min-width: 1024px) {
            .page-content-area {
                padding: 2rem;
                max-width: 100%;
            }

            .live-stream-header {
                min-height: 70px;
            }

            .live-stream-subtitle {
                font-size: 2.25rem;
            }

            .live-stream-main-title {
                font-size: 2.25rem;
            }

            .live-stream-title-container {
                padding-right: 100px; /* Space for the button */
            }

            #stream-container {
                min-height: 70vh;
            }
        }

        /* Tablet styles */
        @media (min-width: 768px) and (max-width: 1023px) {
            .page-content-area {
                padding: 1.5rem;
            }

            .live-stream-header {
                flex-direction: row;
                gap: 1rem;
                min-height: 60px;
            }

            .live-stream-subtitle {
                font-size: 2rem;
            }

            .live-stream-main-title {
                font-size: 2rem;
            }

            .live-stream-title-container {
                padding-right: 90px; /* Space for the button */
            }
        }

        /* Mobile styles */
        @media (max-width: 767px) {
            .page-wrapper {
                padding: 0;
            }

            .page-content-area {
                padding: 1rem;
                margin: 0;
            }

            .live-stream-header {
                flex-direction: column;
                text-align: left;
                gap: 0.75rem;
                margin-bottom: 1rem;
                align-items: flex-start;
                min-height: 50px;
            }

            .live-stream-subtitle {
                font-size: 1.75rem;
            }

            .live-stream-main-title {
                font-size: 1.75rem;
            }

            .live-stream-title-container {
                width: 100%;
                padding-right: 80px; /* Space for the button */
            }

            .live-stream-back-btn {
                position: absolute;
                top: 0;
                right: 0;
                width: auto;
                max-width: 200px;
                margin: 0;
            }

            #stream-container {
                min-height: 50vh;
            }
        }

        /* Small mobile devices */
        @media (max-width: 480px) {
            .page-content-area {
                padding: 0.75rem;
            }

            .live-stream-subtitle {
                font-size: 1.5rem;
            }

            .live-stream-main-title {
                font-size: 1.5rem;
            }

            .live-stream-title-container {
                padding-right: 70px; /* Space for the button */
            }

            .live-stream-back-btn {
                font-size: 0.875rem;
                padding: 0.5rem 1rem;
            }

            #stream-container {
                min-height: 40vh;
            }
        }

        /* Ensure video is responsive */
        #live-stream-player {
            width: 100% !important;
            height: 100% !important;
        }

        /* Landscape orientation for mobile */
        @media (max-width: 767px) and (orientation: landscape) {
            #stream-container {
                min-height: 45vh;
            }
        }

        /* Large screens */
        @media (min-width: 1440px) {
            .page-content-area {
                max-width: 1400px;
                margin: 0 auto;
                padding: 2.5rem;
            }

            .live-stream-subtitle {
                font-size: 2.5rem;
            }

            .live-stream-main-title {
                font-size: 2.5rem;
            }

            .live-stream-title-container {
                padding-right: 120px; /* More space for the button on large screens */
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

        // Handle window resize
        window.addEventListener("resize", function() {
            const player = document.getElementById("live-stream-player");
            if (player) {
                player.style.width = "100%";
                player.style.height = "100%";
            }
        });
    });
    </script>
</x-layouts.app>
