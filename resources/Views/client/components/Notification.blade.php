 @if(isset($_SESSION['success']))
        <div id="custom-popup" style="
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
            z-index: 99999;
            text-align: center;
            animation: popup-fadein 0.3s ease;
        ">
            <div class="checkmark-circle">
                <div class="background"></div>
                <div class="checkmark draw"></div>
            </div>
            <p id="popup-message" style="margin-top: 15px; font-weight: bold;">
                {{ $_SESSION['success'] }}
            </p>
        </div>
        @unset($_SESSION['success'])
    @endif
    <script>
         setTimeout(() => {
            const popup = document.getElementById('custom-popup');
            if (popup) popup.style.display = 'none';
        }, 3000);
    </script>