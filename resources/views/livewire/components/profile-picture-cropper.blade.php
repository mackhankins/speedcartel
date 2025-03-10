<div>
    {{-- Alpine.js component for handling the cropping functionality --}}
    <div x-data="{
            photoPreview: null,
            isCropping: false,
            croppie: null,

            initCroppie() {
                const container = document.getElementById('croppie-container');
                if (!container) {
                    return;
                }

                // Clear previous content
                container.innerHTML = '';

                // Create image element
                const img = document.createElement('img');
                img.src = this.photoPreview;
                container.appendChild(img);

                // Initialize Croppie
                const initCroppieInstance = () => {
                    try {
                        if (this.croppie) {
                            this.croppie.destroy();
                            this.croppie = null;
                        }

                        // Calculate responsive dimensions
                        const isMobile = window.innerWidth < 768;
                        const scale = isMobile ? 0.5 : 1; // 50% size on mobile

                        this.croppie = new Croppie(img, {
                            viewport: {
                                width: {{ $viewportWidth }} * scale,
                                height: {{ $viewportHeight }} * scale,
                                type: '{{ $cropperType }}'
                            },
                            boundary: {
                                width: {{ $boundaryWidth }} * scale,
                                height: {{ $boundaryHeight }} * scale
                            },
                            enableZoom: true,
                            enableExif: true,
                            enforceBoundary: true,
                            mouseWheelZoom: true
                        });
                    } catch (error) {
                        this.isCropping = false;
                    }
                };

                if (img.complete) {
                    initCroppieInstance();
                } else {
                    img.onload = initCroppieInstance;
                }
            },

            saveCroppedImage() {
                if (!this.croppie) {
                    return;
                }

                try {
                    this.croppie.result({
                        type: 'base64',
                        size: {
                            width: {{ $viewportWidth }},
                            height: {{ $viewportHeight }}
                        },
                        format: 'jpeg',
                        quality: {{ $imageQuality }}
                    }).then(base64 => {
                        this.photoPreview = base64;
                        @this.croppedImage = base64;
                        @this.saveCroppedImage();
                        this.isCropping = false;
                        if (this.croppie) {
                            this.croppie.destroy();
                            this.croppie = null;
                        }
                    }).catch(error => {
                        // Handle error silently
                    });
                } catch (error) {
                    // Handle error silently
                }
            },

            cancelCrop() {
                if (this.croppie) {
                    this.croppie.destroy();
                    this.croppie = null;
                }
                this.isCropping = false;
                @this.closeCroppieModal();
            }
        }" x-init="
            // Check if we have a model with a profile picture
            @if($model && $model->{$modelPhotoField})
                photoPreview = '{{ $model->profile_photo_url }}';
            @endif

            $watch('$wire.tempImageUrl', value => {
                if (value) {
                    photoPreview = value;
                    isCropping = true;
                    setTimeout(() => {
                        initCroppie();
                    }, 200);
                }
            });

            // Listen for the profile-picture-init event
            $wire.on('profile-picture-init', ({ url }) => {
                photoPreview = url;
            });

            // Add script to check if Croppie is loaded
            const script = document.createElement('script');
            script.textContent = `
                if (typeof Croppie === 'undefined') {
                    console.error('Croppie is not loaded. Please include the Croppie library.');
                }
            `;
            document.head.appendChild(script);
        " @profile-pic-reset.window="photoPreview = null">
        <!-- Upload UI -->
        <div x-show="!isCropping" class="relative">
            <input type="file" class="sr-only" wire:model="photo" x-ref="photo" accept="image/*">

            <div class="flex items-center space-x-6">
                @if($showPreview)
                    <div class="flex-shrink-0">
                        <div
                            class="relative h-{{ $previewSize }} w-{{ $previewSize }} {{ $cropperType === 'circle' ? 'rounded-full' : 'rounded-lg' }} overflow-hidden bg-gray-100 dark:bg-gray-700">
                            <template x-if="photoPreview">
                                <img :src="photoPreview"
                                    class="h-{{ $previewSize }} w-{{ $previewSize }} {{ $cropperType === 'circle' ? 'rounded-full' : 'rounded-lg' }} object-cover"
                                    alt="Profile preview">
                            </template>
                            <template x-if="!photoPreview">
                                <div
                                    class="h-{{ $previewSize }} w-{{ $previewSize }} {{ $cropperType === 'circle' ? 'rounded-full' : 'rounded-lg' }} flex items-center justify-center bg-gray-100 dark:bg-gray-700">
                                    <svg class="h-{{ (int) ($previewSize / 2) }} w-{{ (int) ($previewSize / 2) }} text-gray-400"
                                        fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                            </template>
                        </div>
                    </div>
                @endif

                <div>
                    <button type="button"
                        class="px-4 py-2 {{ $disabled ? 'bg-gray-400 cursor-not-allowed' : 'bg-primary-600 hover:bg-primary-700' }} text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                        @click="$refs.photo.click()" {{ $disabled ? 'disabled' : '' }}
                        title="{{ $disabled ? 'Pending Approval' : '' }}">
                        {{ $buttonText }}
                    </button>
                    <p
                        class="mt-1 text-xs {{ $disabled ? 'text-amber-600 dark:text-amber-400' : 'text-gray-500 dark:text-gray-400' }}">
                        {{ $disabled ? 'Pending Approval' : $helpText }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Cropping UI -->
        <div x-show="isCropping"
            class="mt-4 p-6 bg-white dark:bg-darker-gray rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 w-full md:max-w-2xl lg:max-w-4xl mx-auto">
            <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                {{ $cropTitle }}
            </h4>

            <div id="croppie-container" class="croppie-container mx-auto"></div>

            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" @click="cancelCrop()"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    {{ $cancelButtonText }}
                </button>
                <button type="button" @click="saveCroppedImage()"
                    class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    {{ $saveButtonText }}
                </button>
            </div>
        </div>
    </div>
</div>