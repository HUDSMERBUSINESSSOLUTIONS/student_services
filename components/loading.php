<?php
function showLoading() {
    echo '
    <div id="loading" style="
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    ">
        <div style="
            width: 120px;
            height: 120px;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        ">
            <!-- Outer Spinning Circle -->
            <div style="
                width: 100%;
                height: 100%;
                border: 4px solid #6366F1;
                border-top: 8px solid transparent;
                border-radius: 50%;
                animation: spin 1.2s linear infinite;
                position: absolute;
            "></div>

            <!-- Inner Spinning Circle -->
            <div style="
                width: 80%;
                height: 80%;
                border: 3px solid #6366F1;
                border-top: 6px solid transparent;
                border-radius: 50%;
                animation: spinReverse 1s linear infinite;
                position: absolute;
            "></div>

            <!-- Fixed Image -->
            <img src="assets/images/loadinglogo.png" alt="Loading" style="
                width: 50%;
                height: 50%;
                border-radius: 50%;
                position: relative;
                z-index: 1;
            ">
        </div>
    </div>
    <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        @keyframes spinReverse {
            from { transform: rotate(360deg); }
            to { transform: rotate(0deg); }
        }
    </style>
    <script>
        setTimeout(() => {
            document.getElementById("loading").style.display = "none";
        }, 2000); // Loading duration: 2 seconds
    </script>';
}
?>