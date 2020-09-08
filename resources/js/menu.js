var form = $("#form-search");
var buttonToggle = $("#section-form-search-toggle");
var mask = $("#form-search-wrapper-mask");

if (isMobile()) {
    form.slideUp();
    form.attr("data-is-collapsed", "true")
    mask.hide();
}

$(window).resize(function() {
    if (isMobile()) {
        unBindEvents();
        bindEvents();

    } else {
        form.slideDown();
        form.attr("data-is-collapsed", "false")
        mask.hide();
        unBindEvents();
    }
});

if (isMobile()) {
    bindEvents();
}

function bindEvents() {
    mask.click(function(event) {
        event.stopPropagation();
        form.slideUp();
        form.attr("data-is-collapsed", "true")
        mask.hide();
    });

    buttonToggle.click(function(event) {
        event.stopPropagation();
        var isCollapsed = form.attr("data-is-collapsed");

        if (isCollapsed == 'true') {
            form.slideDown();
            form.attr("data-is-collapsed", "false")
            mask.show(500);
        } else {
            form.slideUp();
            form.attr("data-is-collapsed", "true")
            mask.hide();
        }
    });
}

function unBindEvents() {
    mask.unbind();
    buttonToggle.unbind();
}

function isMobile() {
    return window.matchMedia("only screen and (max-width: 991px)").matches;
}

$("#section-form-search-toggle a").click(function(event) {
    event.stopPropagation();
});
