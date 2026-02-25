<div class="max-w-md mx-auto p-6 bg-white rounded shadow">
    <div class="main-wrap">

        <div class="nav-header bg-transparent shadow-none border-0">
            <div class="nav-top w-100">
                <a href="./"><i class="feather-zap text-success display1-size me-2 ms-0"></i><span class="d-inline-block fredoka-font ls-3 fw-600 text-current font-xxl logo-text mb-0">MNTI. </span> </a>
                <a href="#" class="mob-menu ms-auto me-2 chat-active-btn"><i class="feather-message-circle text-grey-900 font-sm btn-round-md bg-greylight"></i></a>
                <a href="default-video.html" class="mob-menu me-2"><i class="feather-video text-grey-900 font-sm btn-round-md bg-greylight"></i></a>
                <a href="#" class="me-2 menu-search-icon mob-menu"><i class="feather-search text-grey-900 font-sm btn-round-md bg-greylight"></i></a>
                <button class="nav-menu me-0 ms-2"></button>

                <a href="/login" class="header-btn d-none d-lg-block bg-dark fw-500 text-white font-xsss p-3 ms-auto w100 text-center lh-20 rounded-xl" >Login</a>

            </div>
            
            
        </div>

        <div class="row">
            <div class="col-xl-5 d-none d-xl-block p-0 vh-100 bg-image-cover bg-no-repeat" style="background-image: url('/assets/user-assets/images/login-bg.jpg');"></div>
            <div class="col-xl-7 vh-100 align-items-center d-flex bg-white rounded-3 overflow-hidden">
                <div class="card shadow-none border-0 ms-auto me-auto login-card">
                    <div class="card-body rounded-0 text-left">
                        <h2 class="fw-700 display1-size display2-md-size mb-3">REGISTER into <br>your account</h2>
                       
                         <form wire:submit.prevent="submit" novalidate>
    {{-- Name --}}
    <div class="form-group icon-input mb-3">
        <i class="font-sm ti-user text-grey-500 pe-0"></i>
        <input
            type="text"
            wire:model.defer="full_name"
            class="style2-input ps-5 form-control text-grey-900 font-xsss fw-600 @error('full_name') is-invalid @enderror"
            placeholder="Your Full Name"
            aria-label="Full Name"
        >
        @error('full_name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- USN --}}
    <div class="form-group icon-input mb-3">
        <i class="font-sm ti-id-badge text-grey-500 pe-0"></i>
        <input
            type="text"
            wire:model.defer="usn"
            class="style2-input ps-5 form-control text-grey-900 font-xsss fw-600 @error('usn') is-invalid @enderror"
            placeholder="USN (e.g. 1RV17CS001)"
            aria-label="USN"
        >
        @error('usn')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        <div class="form-text text-grey-500 font-xss mt-1">
            Provide USN if you're in the alumni/student list.
        </div>
    </div>

    {{-- Email --}}
    <div class="form-group icon-input mb-3">
        <i class="font-sm ti-email text-grey-500 pe-0"></i>
        <input
            type="email"
            wire:model.defer="email"
            class="style2-input ps-5 form-control text-grey-900 font-xsss fw-600 @error('email') is-invalid @enderror"
            placeholder="you@example.com"
            aria-label="Email"
        >
        @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- <div class="mb-3">
            <label class="block text-sm">Phone (with country code)</label>
            <input id="phoneInput" type="text" wire:model.defer="phone" class="w-full border p-2" placeholder="+91xxxxxxxxxx">
            @error('phone') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <!-- invisible container for firebase reCAPTCHA -->
            <div id="recaptcha-container"></div>
        </div>

        <div class="mb-3 flex space-x-2">
            <button type="button" id="sendOtpBtn" class="px-3 py-2 bg-blue-500 text-white rounded">Send OTP</button>
            <button type="button" id="resendOtpBtn" class="px-3 py-2 bg-gray-300 text-black rounded hidden">Resend</button>
        </div>

        <div class="mb-3" id="otpSection" style="display:none;">
            <label class="block text-sm">Enter OTP</label>
            <input id="otpInput" type="text" class="w-full border p-2" placeholder="123456">
            <button type="button" id="verifyOtpBtn" class="mt-2 px-4 py-2 bg-green-600 text-white rounded">Verify OTP</button>
            <div id="otpMessage" class="text-sm mt-2"></div>
        </div> --}}

    {{-- Password --}}
    <div class="form-group icon-input mb-3">
        <i class="font-sm ti-lock text-grey-500 pe-0"></i>
        <input
            type="password"
            wire:model.defer="password"
            class="style2-input ps-5 form-control text-grey-900 font-xss ls-3 @error('password') is-invalid @enderror"
            placeholder="Password"
            aria-label="Password"
        >
        @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- Confirm Password --}}
    <div class="form-group icon-input mb-4">
        <i class="font-sm ti-lock text-grey-500 pe-0"></i>
        <input
            type="password"
            wire:model.defer="password_confirmation"
            class="style2-input ps-5 form-control text-grey-900 font-xss ls-3"
            placeholder="Confirm Password"
            aria-label="Confirm Password"
        >
    </div>

    {{-- Submit Button --}}
    <button
        type="submit"
        class="form-control text-center style2-input text-white fw-600 bg-dark border-0 p-0 "
    >
        Register
    </button>
</form>

   
                       
                    </div>
                </div> 
            </div>
        </div>
    </div>

    <!-- Modal Login -->
    <div class="modal bottom fade" style="overflow-y: scroll;" id="Modallogin" tabindex="-1" role="dialog">
         <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="ti-close text-grey-500"></i></button>
                <div class="modal-body p-3 d-flex align-items-center bg-none">
                    <div class="card shadow-none rounded-0 w-100 p-2 pt-3 border-0">
                        <div class="card-body rounded-0 text-left p-3">
                            <h2 class="fw-700 display1-size display2-md-size mb-4">Login into <br>your account</h2>
                            <form>
                                
                                <div class="form-group icon-input mb-3">
                                    <i class="font-sm ti-email text-grey-500 pe-0"></i>
                                    <input type="text" class="style2-input ps-5 form-control text-grey-900 font-xsss fw-600" placeholder="Your Email Address">                        
                                </div>
                                <div class="form-group icon-input mb-1">
                                    <input type="Password" class="style2-input ps-5 form-control text-grey-900 font-xss ls-3" placeholder="Password">
                                    <i class="font-sm ti-lock text-grey-500 pe-0"></i>
                                </div>
                                <div class="form-check text-left mb-3">
                                    <input type="checkbox" class="form-check-input mt-2" id="exampleCheck2">
                                    <label class="form-check-label font-xsss text-grey-500" for="exampleCheck2">Remember me</label>
                                    <a href="forgot.html" class="fw-600 font-xsss text-grey-700 mt-1 float-right">Forgot your Password?</a>
                                </div>
                            </form>
                             
                            <div class="col-sm-12 p-0 text-left">
                                <div class="form-group mb-1"><a href="#" class="form-control text-center style2-input text-white fw-600 bg-dark border-0 p-0 ">Login</a></div>
                                <h6 class="text-grey-500 font-xsss fw-500 mt-0 mb-0 lh-32">Dont have account <a href="register.html" class="fw-700 ms-1">Register</a></h6>
                            </div>
                            <div class="col-sm-12 p-0 text-center mt-3 ">
                                
                                <h6 class="mb-0 d-inline-block bg-white fw-600 font-xsss text-grey-500 mb-4">Or, Sign in with your social account </h6>
                                <div class="form-group mb-1"><a href="#" class="form-control text-left style2-input text-white fw-600 bg-facebook border-0 p-0 mb-2"><img src="/assets/user-assets/images/icon-1.png" alt="icon" class="ms-2 w40 mb-1 me-5"> Sign in with Google</a></div>
                                <div class="form-group mb-1"><a href="#" class="form-control text-left style2-input text-white fw-600 bg-twiiter border-0 p-0 "><img src="/assets/user-assets/images/icon-3.png" alt="icon" class="ms-2 w40 mb-1 me-5"> Sign in with Facebook</a></div>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Register -->
    <div class="modal bottom fade" style="overflow-y: scroll;" id="Modalregister" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="ti-close text-grey-500"></i></button>
                <div class="modal-body p-3 d-flex align-items-center bg-none">
                    <div class="card shadow-none rounded-0 w-100 p-2 pt-3 border-0">
                        <div class="card-body rounded-0 text-left p-3">
                            <h2 class="fw-700 display1-size display2-md-size mb-4">Create <br>your account</h2>                        
                            <form>
                                
                                <div class="form-group icon-input mb-3">
                                    <i class="font-sm ti-user text-grey-500 pe-0"></i>
                                    <input type="text" class="style2-input ps-5 form-control text-grey-900 font-xsss fw-600" placeholder="Your Name">                        
                                </div>
                                <div class="form-group icon-input mb-3">
                                    <i class="font-sm ti-email text-grey-500 pe-0"></i>
                                    <input type="text" class="style2-input ps-5 form-control text-grey-900 font-xsss fw-600" placeholder="Your Email Address">                        
                                </div>
                                <div class="form-group icon-input mb-3">
                                    <input type="Password" class="style2-input ps-5 form-control text-grey-900 font-xss ls-3" placeholder="Password">
                                    <i class="font-sm ti-lock text-grey-500 pe-0"></i>
                                </div>
                                <div class="form-group icon-input mb-1">
                                    <input type="Password" class="style2-input ps-5 form-control text-grey-900 font-xss ls-3" placeholder="Confirm Password">
                                    <i class="font-sm ti-lock text-grey-500 pe-0"></i>
                                </div>
                                <div class="form-check text-left mb-3">
                                    <input type="checkbox" class="form-check-input mt-2" id="exampleCheck3">
                                    <label class="form-check-label font-xsss text-grey-500" for="exampleCheck3">Accept Term and Conditions</label>
                                    <!-- <a href="#" class="fw-600 font-xsss text-grey-700 mt-1 float-right">Forgot your Password?</a> -->
                                </div>
                            </form>
                             
                            <div class="col-sm-12 p-0 text-left">
                                <div class="form-group mb-1"><a href="#" class="form-control text-center style2-input text-white fw-600 bg-dark border-0 p-0 ">Register</a></div>
                                <h6 class="text-grey-500 font-xsss fw-500 mt-0 mb-0 lh-32">Already have account <a href="login.html" class="fw-700 ms-1">Login</a></h6>
                            </div>
                            <div class="col-sm-12 p-0 text-center mt-3 ">
                                
                                <h6 class="mb-0 d-inline-block bg-white fw-600 font-xsss text-grey-500 mb-4">Or, Sign in with your social account </h6>
                                <div class="form-group mb-1"><a href="#" class="form-control text-left style2-input text-white fw-600 bg-facebook border-0 p-0 "><img src="/assets/user-assets/images/icon-1.png" alt="icon" class="ms-2 w40 mb-1 me-5"> Sign in with Google</a></div>
                                <div class="form-group mb-1"><a href="#" class="form-control text-left style2-input text-white fw-600 bg-twiiter border-0 p-0 "><img src="/assets/user-assets/images/icon-3.png" alt="icon" class="ms-2 w40 mb-1 me-5"> Sign in with Facebook</a></div>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    

<!-- Firebase SDKs -->
<script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-auth-compat.js"></script>

<script>
    // Replace with your Firebase config
    const firebaseConfig = {
        apiKey: "AIzaSyC4OCpuax9NBcKQffNQnLpv2tWKXpTqDQw",
        authDomain: "alumni-app-f1b83.firebaseapp.com",
        projectId: "alumni-app-f1b83",
        storageBucket: "alumni-app-f1b83.firebasestorage.app",
        messagingSenderId: "826263299710",
        appId: "1:826263299710:web:87f97a01a1df56334d5a2f",
        measurementId: "G-D789NYDN3X"
    };

    firebase.initializeApp(firebaseConfig);
    const auth = firebase.auth();

    // Render reCAPTCHA
    window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
        size: 'invisible',
        callback: (response) => {
            // reCAPTCHA solved, allow send OTP
            console.log('recaptcha solved');
        }
    });

    let confirmationResult = null;
    const sendOtpBtn = document.getElementById('sendOtpBtn');
    const resendBtn = document.getElementById('resendOtpBtn');
    const otpSection = document.getElementById('otpSection');
    const otpMessage = document.getElementById('otpMessage');

    sendOtpBtn.addEventListener('click', async () => {
        const phone = document.getElementById('phoneInput').value.trim();
        if (!phone) {
            alert('Enter phone number with country code');
            return;
        }

        try {
            otpMessage.textContent = 'Sending OTP...';
            const appVerifier = window.recaptchaVerifier;
            confirmationResult = await auth.signInWithPhoneNumber(phone, appVerifier);
            otpMessage.textContent = 'OTP sent. Check your phone.';
            otpSection.style.display = 'block';
            resendBtn.classList.remove('hidden');
        } catch (err) {
            console.error(err);
            alert('Failed to send OTP: ' + (err.message || err));
            // reset reCAPTCHA
            window.recaptchaVerifier.render().then(function(widgetId) {
                grecaptcha.reset(widgetId);
            });
        }
    });

    document.getElementById('verifyOtpBtn').addEventListener('click', async () => {
        const code = document.getElementById('otpInput').value.trim();
        if (!confirmationResult) { alert('No OTP request in progress'); return; }
        try {
            otpMessage.textContent = 'Verifying...';
            const userCredential = await confirmationResult.confirm(code);
            // We have signed-in user on frontend; get ID token
            const idToken = await userCredential.user.getIdToken(/* forceRefresh */ true);
            const uid = userCredential.user.uid;
            const phone = userCredential.user.phoneNumber;

            // Send to server to verify token server-side
            const res = await fetch('{{ route('firebase.verify') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ idToken })
            });

            const data = await res.json();
            if (data.success) {
                otpMessage.textContent = 'Phone verified.';
                // Inform Livewire component the phone is verified
                // Livewire global: use `window.livewire` or dispatch event
                if (window.livewire) {
                    window.livewire.emit('phoneVerified', data.phone, data.uid);
                } else {
                    console.warn('Livewire not found to emit phoneVerified');
                }
            } else {
                otpMessage.textContent = 'Server could not verify token: ' + (data.message || 'unknown');
            }
        } catch (err) {
            console.error(err);
            otpMessage.textContent = 'Verification failed: ' + (err.message || err);
        }
    });

    // Optionally wire Livewire listener to update UI automatically
    document.addEventListener('livewire:load', function () {
        window.livewire.on('phoneVerified', (phone, uid) => {
            // update phone input and set message if needed
            document.getElementById('phoneInput').value = phone;
        });
    });

</script>
</div>
