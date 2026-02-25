<div>
    <div class="middle-sidebar-bottom">
        <div class="middle-sidebar-left">
            <div class="middle-wrap">
                <div class="card w-100 border-0 bg-white shadow-xs p-0 mb-4">
                    <div class="card-body p-4 w-100 bg-current border-0 d-flex rounded-3">
                        <a href="{{ url()->previous() }}" class="d-inline-block mt-2"><i
                                class="ti-arrow-left font-sm text-white"></i></a>
                        <h4 class="font-xs text-white fw-600 ms-4 mb-0 mt-2">Account Details</h4>
                    </div>
                    <div class="card-body p-lg-5 p-4 w-100 border-0 ">
                        <form wire:submit.prevent="saveProfile">
                            <div class="row justify-content-center mb-4">
                                <div class="col-lg-4 text-center">
                                    <figure class="avatar ms-auto me-auto mb-0 mt-2 w100">



                                        @if ($profile?->profile_picture)
                                            <img src="{{ Storage::url($profile->profile_picture) }}" alt="profile"
                                                class="shadow-sm rounded-3 w-100">
                                        @elseif ($profile_picture)
                                            <img src="{{ $profile_picture->temporaryUrl() }}" alt="preview"
                                                class="shadow-sm rounded-3 w-100">
                                        @else
                                            <img src="/default.png" alt="image" class="shadow-sm rounded-3 w-100">
                                        @endif
                                    </figure>

                                    <h2 class="fw-700 font-sm text-grey-900 mt-3">{{ $profile?->display_name }}</h2>
                                    <h4 class="text-grey-500 fw-500 mb-3 font-xsss mb-4">{{ $profile?->location_city }}
                                    </h4>

                                    <label class="btn btn-sm bg-white text-primary fw-600">
                                        <input wire:model="profile_picture" type="file" accept="image/*"
                                            class="d-none">
                                        Upload Photo
                                    </label>
                                    @error('profile_picture')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
    <label class="mont-font fw-600 font-xsss">Who can see your profile?</label>
    <select wire:model.defer="visibility" class="form-control">
        <option value="public">Public</option>
        <option value="alumni_only">Alumni</option>
        <option value="private">Private</option>
    </select>
</div>

                            </div>

                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <div class="form-group">
                                        <label class="mont-font fw-600 font-xsss">First Name</label>
                                        <input wire:model.defer="first_name" type="text" class="form-control">
                                        @error('first_name')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    

                                </div>

                                <div class="col-lg-6 mb-3">
                                    <div class="form-group">
                                        <label class="mont-font fw-600 font-xsss">Last Name</label>
                                        <input wire:model.defer="last_name" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 mb-3">
  <div class="form-group position-relative">
    <label class="mont-font fw-600 font-xsss d-flex justify-content-between align-items-center">
      <span>Email</span>

      <div class="dropdown">
        <a class="text-muted font-xss d-flex align-items-center gap-1" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="feather-lock"></i>
          <span class="text-muted font-xxs">
            {{
              $privacy_settings['email'] === 'public' ? 'Public' :
              ($privacy_settings['email'] === 'alumni_only' ? 'Alumni Only' : 'Private')
            }}
          </span>
        </a>

        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
          <li><a class="dropdown-item" href="#" wire:click.prevent="$set('privacy_settings.email', 'public')">Public</a></li>
          <li><a class="dropdown-item" href="#" wire:click.prevent="$set('privacy_settings.email', 'alumni_only')">Alumni Only</a></li>
          <li><a class="dropdown-item" href="#" wire:click.prevent="$set('privacy_settings.email', 'private')">Private</a></li>
        </ul>
      </div>
    </label>

    <input type="text" value="{{ Auth::user()->email }}" class="form-control" disabled>
  </div>
</div>


                                <div class="col-lg-6 mb-3">
                                    <div class="form-group position-relative">
                                        <label
                                            class="mont-font fw-600 font-xsss d-flex justify-content-between align-items-center">
                                            <span>Phone</span>

                                            <div class="dropdown">
                                                <a class="text-muted font-xss d-flex align-items-center gap-1"
                                                    href="#" role="button" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="feather-lock"></i>
                                                    <span class="text-muted font-xxs">
                                                        {{ $privacy_settings['phone'] === 'public'
                                                            ? 'Public'
                                                            : ($privacy_settings['phone'] === 'alumni_only'
                                                                ? 'Alumni Only'
                                                                : 'Private') }}
                                                    </span>
                                                </a>

                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                    <li><a class="dropdown-item" href="#"
                                                            wire:click.prevent="$set('privacy_settings.phone', 'public')">Public</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="#"
                                                            wire:click.prevent="$set('privacy_settings.phone', 'alumni_only')">Alumni
                                                            Only</a></li>
                                                    <li><a class="dropdown-item" href="#"
                                                            wire:click.prevent="$set('privacy_settings.phone', 'private')">Private</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </label>

                                        <input wire:model.defer="extra.phone" type="text" class="form-control"
                                            placeholder="Mobile">
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <div class="form-group">
                                        <label class="mont-font fw-600 font-xsss">Display Name</label>
                                        <input type="text" wire:model.defer="display_name" class="form-control">

                                    </div>
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <div class="form-group">
                                        <label class="mont-font fw-600 font-xsss">City You Live</label>
                                        <input wire:model.defer="location_city" type="text" class="form-control"
                                            placeholder="City">
                                    </div>
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-lg-6 mb-3">
  <div class="form-group position-relative">
    <label class="mont-font fw-600 font-xsss d-flex justify-content-between align-items-center">
      <span>Department</span>

      <div class="dropdown">
        <a class="text-muted font-xss d-flex align-items-center gap-1" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="feather-lock"></i>
          <span class="text-muted font-xxs">
            {{
              $privacy_settings['department'] === 'public' ? 'Public' :
              ($privacy_settings['department'] === 'alumni_only' ? 'Alumni Only' : 'Private')
            }}
          </span>
        </a>

        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
          <li><a class="dropdown-item" href="#" wire:click.prevent="$set('privacy_settings.department', 'public')">Public</a></li>
          <li><a class="dropdown-item" href="#" wire:click.prevent="$set('privacy_settings.department', 'alumni_only')">Alumni Only</a></li>
          <li><a class="dropdown-item" href="#" wire:click.prevent="$set('privacy_settings.department', 'private')">Private</a></li>
        </ul>
      </div>
    </label>

    <select wire:model="department_id" class="form-control">
      <option value="">-- Select department --</option>
      @foreach ($departments as $dept)
        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
      @endforeach
    </select>

    @error('department_id')
      <div class="text-danger small">{{ $message }}</div>
    @enderror
  </div>
</div>


                                <div class="col-lg-6 mb-3">
  <div class="form-group position-relative">
    <label class="mont-font fw-600 font-xsss d-flex justify-content-between align-items-center">
      <span>Course</span>

      <div class="dropdown">
        <a class="text-muted font-xss d-flex align-items-center gap-1" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="feather-lock"></i>
          <span class="text-muted font-xxs">
            {{
              $privacy_settings['course'] === 'public' ? 'Public' :
              ($privacy_settings['course'] === 'alumni_only' ? 'Alumni Only' : 'Private')
            }}
          </span>
        </a>

        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
          <li><a class="dropdown-item" href="#" wire:click.prevent="$set('privacy_settings.course', 'public')">Public</a></li>
          <li><a class="dropdown-item" href="#" wire:click.prevent="$set('privacy_settings.course', 'alumni_only')">Alumni Only</a></li>
          <li><a class="dropdown-item" href="#" wire:click.prevent="$set('privacy_settings.course', 'private')">Private</a></li>
        </ul>
      </div>
    </label>

    <select wire:model.defer="course_id" class="form-control">
      <option value="">-- Select course --</option>
      @foreach ($courses as $course)
        <option value="{{ $course->id }}">{{ $course->name }}</option>
      @endforeach
    </select>

    @error('course_id')
      <div class="text-danger small">{{ $message }}</div>
    @enderror
  </div>
</div>

                            </div>

                            <div class="row">
                               <div class="col-lg-4 mb-3">
  <div class="form-group position-relative">
    <label class="mont-font fw-600 font-xsss d-flex justify-content-between align-items-center">
      <span>Batch Year</span>

      <div class="dropdown">
        <a class="text-muted font-xss d-flex align-items-center gap-1" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="feather-lock"></i>
          <span class="text-muted font-xxs">
            {{
              $privacy_settings['batch_year'] === 'public' ? 'Public' :
              ($privacy_settings['batch_year'] === 'alumni_only' ? 'Alumni Only' : 'Private')
            }}
          </span>
        </a>

        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
          <li><a class="dropdown-item" href="#" wire:click.prevent="$set('privacy_settings.batch_year', 'public')">Public</a></li>
          <li><a class="dropdown-item" href="#" wire:click.prevent="$set('privacy_settings.batch_year', 'alumni_only')">Alumni Only</a></li>
          <li><a class="dropdown-item" href="#" wire:click.prevent="$set('privacy_settings.batch_year', 'private')">Private</a></li>
        </ul>
      </div>
    </label>

    <input wire:model.defer="batch_year" type="number" class="form-control">
  </div>
</div>


                                <div class="col-lg-8 mb-3"> 
  <div class="form-group position-relative" x-data="{ loading: false, fetched: false }">

    <label class="mont-font fw-600 font-xsss d-flex justify-content-between align-items-center mb-1">
      <span>Location</span>

      <div class="dropdown">
        <a class="text-muted font-xss d-flex align-items-center gap-1" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="feather-lock"></i>
          <span class="text-muted font-xxs">
            {{
              $privacy_settings['location'] === 'public' ? 'Public' :
              ($privacy_settings['location'] === 'alumni_only' ? 'Alumni Only' : 'Private')
            }}
          </span>
        </a>

        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
          <li><a class="dropdown-item" href="#" wire:click.prevent="$set('privacy_settings.location', 'public')">Public</a></li>
          <li><a class="dropdown-item" href="#" wire:click.prevent="$set('privacy_settings.location', 'alumni_only')">Alumni Only</a></li>
          <li><a class="dropdown-item" href="#" wire:click.prevent="$set('privacy_settings.location', 'private')">Private</a></li>
        </ul>
      </div>
    </label>

    <button 
      type="button" 
      style="margin-top: 34px;"
      @click="
        if (fetched) return;
        loading = true;
        getLocation()
          .then(() => { fetched = true })
          .catch(() => { fetched = false })
          .finally(() => loading = false);
      "
      class="btn btn-primary d-flex align-items-center gap-2"
      :disabled="loading || fetched"
    >
      <template x-if="!loading && !fetched">
        <span>📍 Use My Location</span>
      </template>

      <template x-if="loading">
        <span>
          <span class="spinner-border spinner-border-sm me-1" role="status"></span>
          Fetching...
        </span>
      </template>

      <template x-if="fetched && !loading">
        <span>Location Fetched</span>
      </template>
    </button>

    <!-- Hidden fields -->
    <input type="hidden" wire:model="latitude">
    <input type="hidden" wire:model="longitude">

  </div>
</div>



                            </div>

                            <div>

                            </div>

                            <div class="row">
                                <div class="col-lg-12 mb-3">
  <div class="form-group position-relative">
    <label class="mont-font fw-600 font-xsss d-flex justify-content-between align-items-center">
      <span>Address</span>

      <div class="dropdown">
        <a class="text-muted font-xss d-flex align-items-center gap-1" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="feather-lock"></i>
          <span class="text-muted font-xxs">
            {{
              $privacy_settings['address'] === 'public' ? 'Public' :
              ($privacy_settings['address'] === 'alumni_only' ? 'Alumni Only' : 'Private')
            }}
          </span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
          <li><a class="dropdown-item" href="#" wire:click.prevent="$set('privacy_settings.address', 'public')">Public</a></li>
          <li><a class="dropdown-item" href="#" wire:click.prevent="$set('privacy_settings.address', 'alumni_only')">Alumni Only</a></li>
          <li><a class="dropdown-item" href="#" wire:click.prevent="$set('privacy_settings.address', 'private')">Private</a></li>
        </ul>
      </div>
    </label>

    <input wire:model.defer="location_state" type="text" class="form-control" placeholder="State / Address line">
  </div>
</div>


                                <div class="col-lg-12 mb-3">
  <div class="form-group position-relative">
    <label class="mont-font fw-600 font-xsss d-flex justify-content-between align-items-center">
      <span>Status</span>

      <div class="dropdown">
        <a class="text-muted font-xss d-flex align-items-center gap-1" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="feather-lock"></i>
          <span class="text-muted font-xxs">
            {{
              $privacy_settings['headline'] === 'public' ? 'Public' :
              ($privacy_settings['headline'] === 'alumni_only' ? 'Alumni Only' : 'Private')
            }}
          </span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
          <li><a class="dropdown-item" href="#" wire:click.prevent="$set('privacy_settings.headline', 'public')">Public</a></li>
          <li><a class="dropdown-item" href="#" wire:click.prevent="$set('privacy_settings.headline', 'alumni_only')">Alumni Only</a></li>
          <li><a class="dropdown-item" href="#" wire:click.prevent="$set('privacy_settings.headline', 'private')">Private</a></li>
        </ul>
      </div>
    </label>

    <input wire:model.defer="headline" type="text" class="form-control">
  </div>
</div>


                                <div class="col-lg-12 mb-3">
  <div class="form-group position-relative">
    <label class="mont-font fw-600 font-xsss d-flex justify-content-between align-items-center">
      <span>Say About You</span>

      <div class="dropdown">
        <a class="text-muted font-xss d-flex align-items-center gap-1" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="feather-lock"></i>
          <span class="text-muted font-xxs">
            {{
              $privacy_settings['about'] === 'public' ? 'Public' :
              ($privacy_settings['about'] === 'alumni_only' ? 'Alumni Only' : 'Private')
            }}
          </span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
          <li><a class="dropdown-item" href="#" wire:click.prevent="$set('privacy_settings.about', 'public')">Public</a></li>
          <li><a class="dropdown-item" href="#" wire:click.prevent="$set('privacy_settings.about', 'alumni_only')">Alumni Only</a></li>
          <li><a class="dropdown-item" href="#" wire:click.prevent="$set('privacy_settings.about', 'private')">Private</a></li>
        </ul>
      </div>
    </label>

    <textarea wire:model.defer="about" class="form-control mb-0 p-3 h100 bg-greylight lh-16" rows="5"
      placeholder="Write about yourself..." spellcheck="false"></textarea>
  </div>
</div>

                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="submit"
                                        class="bg-current text-center text-white font-xsss fw-600 p-3 w175 rounded-3 d-inline-block">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- <div class="card w-100 border-0 p-2"></div> -->
                </div>
            </div>

        </div>
        <script>
            function getLocation() {
                return new Promise((resolve, reject) => {
                    if (!navigator.geolocation) {
                        alert('Geolocation not supported');
                        return reject('Geolocation not supported');
                    }
                    navigator.geolocation.getCurrentPosition(
                        function(pos) {
                            Livewire.dispatch('setLocation', [pos.coords.latitude, pos.coords.longitude]);
                            resolve();
                        },
                        function(err) {
                            alert('Unable to fetch location: ' + err.message);
                            reject(err);
                        }, {
                            enableHighAccuracy: true,
                            timeout: 10000
                        }
                    );
                });
            }
        </script>




    </div>
