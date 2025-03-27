<?php
function showToast($message) {
    echo '
    <div id="toast" class="toast">
        <img src="assets/images/loadinglogo.png" alt="Loading" class="loading-logo">
        ' . $message . '
        <div id="progress" class="progress-bar"></div>
    </div>
    <style>
        /* Toast Notification Styling */
        .toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: white;
            color: #6366F1;
            padding: 16px 24px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 0.4s, transform 0.4s;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 250px;
            z-index: 9999;
        }

        /* Loading Logo with Horizontal Rotation */
        .loading-logo {
            width: 30px;
            height: 30px;
            animation: rotate-horizontal 1s linear infinite;
        }

        @keyframes rotate-horizontal {
            from { transform: rotateY(0deg); }
            to { transform: rotateY(360deg); }
        }

        /* Progress Bar */
        .progress-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 4px;
            background-color: #6366F1;
            width: 0%;
            transition: width 2s linear;
        }

        /* Toast Visible State */
        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
    <script>
        function showToast() {
            const toast = document.getElementById("toast");
            const progress = document.getElementById("progress");

            // Show the toast
            toast.classList.add("show");
            progress.style.width = "100%";

            // Hide the toast after 2 seconds
            setTimeout(() => {
                toast.classList.remove("show");
                progress.style.width = "0%";
            }, 2000);
        }

        // Call the toast function on load
        window.onload = showToast;
    </script>
    ';
}
?>