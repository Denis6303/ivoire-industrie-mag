<div id="overlayer"></div>
<span class="loader">
    <span class="loader-inner"></span>
    <span class="ivm-loader-logo-wrap">
        <img src="{{ asset('images/2im_blanc.svg') }}" alt="2IM" class="ivm-loader-logo">
    </span>
</span>

<style>
    #overlayer {
        background: #243e5d !important;
    }
    .loader {
        width: 72px !important;
        height: 72px !important;
        border: 5px solid #243e5d !important;
        position: fixed !important;
        top: 50% !important;
        left: 50% !important;
        margin-top: -36px !important;
        margin-left: -36px !important;
        overflow: hidden;
    }
    .loader-inner {
        background-color: #243e5d !important;
    }
    .ivm-loader-logo-wrap {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
        z-index: 2;
    }
    .ivm-loader-logo {
        width: 42px;
        height: auto;
        display: block;
    }
</style>
