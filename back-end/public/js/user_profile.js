!function(e){var n={};function t(o){if(n[o])return n[o].exports;var r=n[o]={i:o,l:!1,exports:{}};return e[o].call(r.exports,r,r.exports,t),r.l=!0,r.exports}t.m=e,t.c=n,t.d=function(e,n,o){t.o(e,n)||Object.defineProperty(e,n,{configurable:!1,enumerable:!0,get:o})},t.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(n,"a",n),n},t.o=function(e,n){return Object.prototype.hasOwnProperty.call(e,n)},t.p="",t(t.s=44)}({1:function(e,n){var t=Object.assign||function(e){for(var n=1;n<arguments.length;n++){var t=arguments[n];for(var o in t)Object.prototype.hasOwnProperty.call(t,o)&&(e[o]=t[o])}return e};window.getSpinner=function(e){return $("<div />").addClass("spinner spinner--"+e).append($("<div />")).append($("<div />")).append($("<div />")).append($("<div />"))},window.showSpinner=function(e){e.addClass("is-loading").prop("disabled",!0),e.append(getSpinner(30))},window.hideSpinner=function(e){e.removeClass("is-loading").prop("disabled",!1),e.find(".spinner").remove()},window.clearErrors=function(){$(".form-error").removeClass("form-error"),$(".error-text").remove()},window.showError=function(e){$.magnificPopup.open({items:{src:"#error-modal"},type:"inline",closeOnBgClick:!0,callbacks:{elementParse:function(n){$(n.src).find("#error-message").text(e)}}})},window.validateFile=function(e){var n=e.type.match(/(.png)|(.jpeg)|(.jpg)|(.pdf)$/i),t=e.size.toFixed(0)<4194304;return!(!n||!t)},window.scrollToError=function(){$("html, body").animate({scrollTop:$(".form-error:eq(0)").offset().top},500)},window.showConfirmation=function(e,n,t){$.magnificPopup.open({items:{src:"#confirmation-modal"},type:"inline",showCloseBtn:!1,closeOnBgClick:!1,callbacks:{elementParse:function(t){$(t.src).find("#confirmation-message").text(e),$(t.src).find("#accept_action").on("click",function(e){e.preventDefault(),$.magnificPopup.close(),"function"==typeof n&&n()}),$(t.src).find("#reject_action").on("click",function(e){e.preventDefault(),$.magnificPopup.close()})}}})},window.showPopover=function(e){$(".popover").remove();var n=$("<div />").addClass("popover").append($("<i />").addClass("fa fa-check-circle")).append($("<div />").addClass("popover__content").html(e)).append($("<a />").addClass("popover__close").attr("href","").html("Close").on("click",function(e){e.preventDefault(),$(".popover").remove()}));$("body").prepend(n),setTimeout(function(){n.remove()},5e3)},window.showProtectionDialog=function(e){sessionStorage.removeItem("signature"),$.magnificPopup.open({items:{src:"#protection-modal"},type:"inline",showCloseBtn:!0,closeOnBgClick:!0,callbacks:{elementParse:function(n){$(n.src).find("#frm_protection").find('input[name="signature"]').val(""),$(n.src).find("#frm_protection").off("submit").on("submit",function(n){n.preventDefault(),sessionStorage.setItem("signature",$(this).find('input[name="signature"]').val()),$.magnificPopup.close(),e()})}}})},window.processProtectionRequest=function(e){var n=sessionStorage.getItem("signature");return n?t({},e,{signature:n}):e},window.processProtectionResponse=function(e,n,t){205==e?"function"==typeof n&&showProtectionDialog(n):"function"==typeof t&&t()}},44:function(e,n,t){e.exports=t(45)},45:function(e,n,t){t(1),$(document).ready(function(){$('select[name="country"]').on("change",function(e){var n=$(this).val();axios.get("/user/states",{params:{country:n}}).then(function(e){$('select[name="state"]').html(e.data.states.map(function(e){return $("<option />").val(e.id).text(e.name).attr("selected",0==e.id?"selected":"")})),$('select[name="area-code"]').html(e.data.codes.map(function(e){return $("<option />").val(e.id).text(e.code).attr("selected",0==e.id?"selected":"")}))}).catch(function(){$('select[name="state"]').html($("<option />").val(0).text("Other state").attr("selected","selected")),$('select[name="area-code"]').html($("<option />").val(0).text("Other code").attr("selected","selected"))})}),$("#save-profile").on("click",function(e){var n=this;e.preventDefault();var t=$(this);showSpinner(t),clearErrors();var o=processProtectionRequest({first_name:$('input[name="f-name"]').val(),last_name:$('input[name="l-name"]').val(),email:$('input[name="email"]').val(),phone_number:$('input[name="tel"]').val(),area_code:$('select[name="area-code"]').val(),country:$('select[name="country"]').val(),state:$('select[name="state"]').val(),city:$('input[name="city"]').val(),address:$('input[name="address"]').val(),postcode:$('input[name="post-code"]').val(),passport:$('input[name="government"]').val(),expiration_date:$('input[name="expiry"]').val(),birth_date:$('input[name="birth"]').val(),birth_country:$('select[name="country-birth"]').val()});axios.post("/user/profile",qs.stringify(o)).then(function(e){hideSpinner(t),processProtectionResponse(e.status,function(){$(n).trigger("click")},function(){$.magnificPopup.open({items:{src:"#profile-modal"},type:"inline",closeOnBgClick:!0})})}).catch(function(e){hideSpinner(t);var n=e.response.data,o=n.errors,r=n.message;422==e.response.status?($.each(o,function(e,n){$(".profile_"+e).addClass("form-error"),$(".profile_"+e).after($("<span />").addClass("error-text").text(n))}),scrollToError()):showError(r)})})})}});