
<style>
    @import url("https://fonts.cdnfonts.com/css/milliard");

    * {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        font-family: "Milliard", sans-serif;
    }

    .mw-ads-holder {
        background: #fff;
        z-index: 99999;
        padding: 4px 7px;
        position: absolute;
        min-height: 40px;
        border: 0;
        left: 0;
        right: 0;
        top: 0;
        width: 100%;
        overflow: hidden;
        border-top: 1px solid #f1f3f4;
        color: #2d2d2d;
        font-size: 14px;
        line-height: 12px;
        font-weight: 500;
        cursor: pointer;
    }

    .mw-ads-holder p {
        margin: 0;
        margin-top: 2px;
    }

    .mw-ads-holder .row {
        display: block;
    }

    .mw-ads-holder .row:after {
        display: block;
        content: "";
        clear: both;
    }

    .mw-ads-holder .row .col {
        float: left;
    }

    .mw-ads-holder .row .col:nth-child(1) {
        padding: 10px 0 10px 10px;
        width: calc(100% - 210px);
    }

    .mw-ads-holder .row .col:nth-child(2) {
        padding: 13px 10px 13px 0;
        width: 210px;
    }

    .mw-ads-holder .text-right {
        text-align: right;
    }

    .mw-ads-holder img {
        float: left;
        margin-right: 15px;
    }

    .mw-ads-holder a {
        color: #1717ff;
        text-decoration: none !important;
    }

    .mw-ads-holder:hover a {

    }

    @media screen and (min-width: 451px) and (max-width: 767px) {
        .hidden-sm {
            display: none;
        }
    }

    @media screen and (max-width: 450px) {
        .hidden-xs {
            display: none;
        }
    }
</style>

<div class="mw-ads-holder" onclick="window.open('https://saas.microweber.bg', '_blank');">
    <div class="row">
        <div class="col">
            This website is developed by <a href="https://saas.microweber.bg" target="_blank">Microweber</a>
        </div>
    </div>
</div>