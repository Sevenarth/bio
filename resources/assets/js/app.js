
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

var moment = require('moment');
var Pjax = require('pjax');
window.MD = require('markdown-it')({
    html: true,
    linkify: true,
    breaks: true
});
require('./specialmde');
window.Slick = require('slick-carousel');
import __ from './translations';

$(function () {
    window.locale = $("html").attr("lang");
    moment.locale(window.locale);

    $('.slideshow').slick({
        dots: true,
        arrows: false
    });

    $(".markdown").each(function() {
        $(this).html(MD.render($(this).text()));
    });

    $(".relative-time").each(function() {
        var date = $(this).text();
        $(this).attr("title", moment(date).format('llll'));
        $(this).text(moment(date).fromNow())
    });

    /*var pjax = new Pjax({
        elements: "a",
        selectors: ["title", "#app"],
        cacheBust: false
    })*/

    document.addEventListener('pjax:send', function() {
        $("body").css("cursor", "progress");
        $(".loading").css("width", "40%");
    });

    document.addEventListener('pjax:complete', function() {
        $(".markdown").each(function() {
        $(this).html(MD.render($(this).text()));
        });
        $("body").css("cursor", "default");
        $(".loading").css("width", "100%");
        setTimeout(function() {
        $(".loading").fadeOut(400, function() {
            $(".loading").css("width", "0%");
            $(".loading").show();
        });
        }, 200);
    });


    $("body").on('click', '.image-field', function() {
        var imageId = $(this).attr("id");
        if(imageId != "image-add") {
        var editMode = $("#images-box").attr("data-editMode");
        $("#images-box").attr("data-editMode", editMode == "true" ? "false" : "true");

        if(editMode == "true")
            $(this).removeClass("active-image");
        else
            $(this).addClass("active-image");

        $("#images-box").children().each(function(i, elt) {
            var current = $(elt);
            var id = current.attr("id");
            if(id == imageId + "-box") {
            if(editMode == "true")
                current.addClass("d-none")
            else
                current.removeClass("d-none");
            } else if(id != imageId + "-wrapper") {
            if(editMode == "true" && !current.hasClass("image-box"))
                current.removeClass("d-none");
            else
                current.addClass("d-none");
            }
        })
        }
    });

    $("body").on('click', '#image-add', function() {
        var quantity = parseInt($("#images-box").attr("data-quantity"));
        if(quantity < 1)
        quantity = 1;

        var index = quantity+1;
        $("#images-box").attr("data-quantity", index);

        $(this).parent().before(`<div id="image-`+index+`-wrapper" class="col-3 my-2 px-3">
        <img id="image-`+index+`" src="/images/package.svg" class="img-fluid rounded border image-field">
        </div>
        <div id="image-`+index+`-box" class="image-box col-9 d-none">
        <div class="rounded border px-3 py-3">
            <fieldset class="form-group">
            <label for="image-`+index+`-field">${__('Picture link')}</label>
            <input type="text" name="images[]" id="image-`+index+`-field" data-target="image-`+index+`" placeholder="http://" class="image-field-input form-control">
            </fieldset>
            <div class="btn-group">
            <button class="btn btn-primary upload-imageBox" data-target="image-`+index+`" type="button">${__('Upload picture')}</button>
            <button type="button" class="btn btn-danger image-remove" data-target="image-`+index+`">${__('Remove picture')}</button>
            </div>
        </div>
        </div>`);
    });

    $("body").on('click', '.image-remove', function() {
        var imageId = $(this).attr("data-target");
        $("#" + imageId + "-box").remove();
        $("#" + imageId + "-wrapper").remove();

        $("#images-box").children().each(function(i, elt) {
        var current = $(elt);
        var id = current.attr("id");
        if(!current.hasClass("image-box"))
            current.removeClass("d-none");
        })
    });

    $("body").on('click', '.upload-imageBox', function () {
        var uploadWindow = window.open($("#images-box").attr("data-page") + "?field=" + $(this).attr("data-target"),'uploader','height=480,width=350');
        if (window.focus)
        uploadWindow.focus()
    });

    var updateImageField = function(obj) {
        if(typeof obj === 'object')
        var current = $(this),
            thumbnail = $("#" + $(this).attr("data-target"));
        else
        var current = $("#"+obj+"-field"),
            thumbnail = $("#"+obj);

        if(current.val().length > 0)
        thumbnail.attr("src", current.val());
        else
        thumbnail.attr("src", '/images/package.svg');
    };

    $("body").on('focusout', '.image-field-input', updateImageField);
    window.updateImageField = updateImageField;

    $("#filter").submit(function() {
        let data = $(this).serializeArray();
        let context = {};

        for(let i in data)
            if(data[i]['value'])
                context[data[i]['name']] = data[i]['value'];

        let query = $.param(context);

        window.location.href = window.location.pathname + (query.length > 0 ? "?" + query : "");
        return false;
    });

    $("#choose-category").change(() => $("#filter").submit());
    $("#choose-country").change(() => $("#filter").submit());


  $("body").on('click', '.remove-confirmation', function() {
    if($(this).attr("data-popover-toggled") == "true") {
      $("#" + $(this).attr("aria-describedby")).remove()
      return true;
    }
    $(this).attr("data-popover-toggled", "true");
    $(this).popover('toggle');
    return false;
  });

  $("body").on('hidden.bs.popover', '.remove-confirmation', function() {
    $(this).attr("data-popover-toggled", "false");
  });

  $("body").on("click", ".select-country", function() {
    var target = $(this).attr("data-target");
    $(`#${target}-country`).val($(this).attr("data-country"));
    $(`#${target}-btn`).text($(this).text());
  });

  window.sample_profile = $("#sample_profile").html();

  $("#add-profile").click(function() {
    var i = parseInt($("#profiles").attr("data-count"))+1;
    var profile = `<div id="profile-${i}-wrapper" class="input-group mb-3"><div class="input-group" id="profile-${i}">` + sample_profile.replace(/profile-sample/g, 'profile-'+i) + `<div class="input-group-append"><button class="btn btn-danger remove-profile" data-target="profile-${i}" type="button"><i class="fa fa-fw fa-trash"></i></button></div></div></div>`;
    $("#profiles").append(profile);
    $("#profiles").attr("data-count", i);
  });

  $("body").on('click', '.remove-profile', function() {
    var index = $(this).attr("data-target");  
    $('#'+index+'-wrapper').remove();
  })
});