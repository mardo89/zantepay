!function(e){var n={};function t(r){if(n[r])return n[r].exports;var i=n[r]={i:r,l:!1,exports:{}};return e[r].call(i.exports,i,i.exports,t),i.l=!0,i.exports}t.m=e,t.c=n,t.d=function(e,n,r){t.o(e,n)||Object.defineProperty(e,n,{configurable:!1,enumerable:!0,get:r})},t.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(n,"a",n),n},t.o=function(e,n){return Object.prototype.hasOwnProperty.call(e,n)},t.p="",t(t.s=48)}({1:function(e,n){var t=Object.assign||function(e){for(var n=1;n<arguments.length;n++){var t=arguments[n];for(var r in t)Object.prototype.hasOwnProperty.call(t,r)&&(e[r]=t[r])}return e};window.getSpinner=function(e){return $("<div />").addClass("spinner spinner--"+e).append($("<div />")).append($("<div />")).append($("<div />")).append($("<div />"))},window.showSpinner=function(e){e.addClass("is-loading").prop("disabled",!0),e.append(getSpinner(30))},window.hideSpinner=function(e){e.removeClass("is-loading").prop("disabled",!1),e.find(".spinner").remove()},window.clearErrors=function(){$(".form-error").removeClass("form-error"),$(".error-text").remove()},window.showError=function(e){$.magnificPopup.open({items:{src:"#error-modal"},type:"inline",closeOnBgClick:!0,callbacks:{elementParse:function(n){$(n.src).find("#error-message").text(e)}}})},window.validateFile=function(e){var n=e.type.match(/(.png)|(.jpeg)|(.jpg)|(.pdf)$/i),t=e.size.toFixed(0)<4194304;return!(!n||!t)},window.scrollToError=function(){$("html, body").animate({scrollTop:$(".form-error:eq(0)").offset().top},500)},window.showConfirmation=function(e,n,t){$.magnificPopup.open({items:{src:"#confirmation-modal"},type:"inline",showCloseBtn:!1,closeOnBgClick:!1,callbacks:{elementParse:function(t){$(t.src).find("#confirmation-message").text(e),$(t.src).find("#accept_action").on("click",function(e){e.preventDefault(),$.magnificPopup.close(),"function"==typeof n&&n()}),$(t.src).find("#reject_action").on("click",function(e){e.preventDefault(),$.magnificPopup.close()})}}})},window.showPopover=function(e){$(".popover").remove();var n=$("<div />").addClass("popover").append($("<i />").addClass("fa fa-check-circle")).append($("<div />").addClass("popover__content").html(e)).append($("<a />").addClass("popover__close").attr("href","").html("Close").on("click",function(e){e.preventDefault(),$(".popover").remove()}));$("body").prepend(n),setTimeout(function(){n.remove()},5e3)},window.showProtectionDialog=function(e){$.magnificPopup.open({items:{src:"#protection-modal"},type:"inline",showCloseBtn:!0,closeOnBgClick:!0,callbacks:{elementParse:function(n){$(n.src).find("#frm_protection").find('input[name="signature"]').val(""),$(n.src).find("#frm_protection").off("submit").on("submit",function(n){n.preventDefault(),sessionStorage.setItem("signature",$(this).find('input[name="signature"]').val()),$.magnificPopup.close(),e()})}}})},window.processProtectionRequest=function(e,n){var r=sessionStorage.getItem("signature");return sessionStorage.removeItem("signature"),t({},n,r?{signature:r}:{action:e})},window.processProtectionResponse=function(e,n,t){205==e?"function"==typeof n&&showProtectionDialog(n):"function"==typeof t&&t()}},48:function(e,n,t){e.exports=t(49)},49:function(e,n,t){t(1);var r=function(e,n,t){var r="width="+n+", height="+t+", resizable=no, scrollbars=yes, left="+(screen.availWidth-n)/2+", top="+(screen.availHeight-t)/2;window.open(e,"_blank",r)};$(document).ready(function(){$("#invite-friend").on("click",function(e){e.preventDefault();var n=$("#invite-friend");showSpinner(n),clearErrors();var t=$("#friend-email").val();axios.post("/user/invite-friend",qs.stringify({email:t})).then(function(e){hideSpinner(n),$('input[name="email"]').val(""),0===$("#invites-list tr td:contains('"+e.data.email+"')").length&&$("#invites-list tbody").prepend($("<tr />").attr("id",e.data.email).append($("<td />").css("width","100").addClass("col-center").append($("<div />").addClass("thumb-60").append($("<img />").attr("src","/images/avatar.png").attr("alt",e.data.email)))).append($("<td />").text(e.data.email)).append($("<td />").append($("<span />").addClass("primary-color").text(e.data.status))).append($("<td />").text("")).append($("<td />").text("")).append($("<td />").css("width","160").addClass("col-center").append($("<a />").attr("href","").addClass("send-link resend-invitation").text("Resend"))))}).catch(function(e){hideSpinner(n),console.error(e);var t=e.response.data,r=t.message,i=t.errors;422==e.response.status?($.each(i,function(e,n){$("#friend-email").parent().addClass("form-error"),$("#friend-email").after($("<span />").addClass("error-text").text(n))}),scrollToError()):showError(r)})}),$("#invites-list").on("click",".resend-invitation",function(e){e.preventDefault();var n=$(this).parents("tr").attr("id");axios.post("/user/invite-friend",qs.stringify({email:n})).catch(function(e){var n=e.response.data.message;showError(n)})}),$("#fb-share").on("click",function(e){e.preventDefault(),r($(this).attr("href"),640,400)}),$("#tw-share").on("click",function(e){e.preventDefault(),r($(this).attr("href"),640,255)}),$("#google-share").on("click",function(e){e.preventDefault(),r($(this).attr("href"),400,480)})})}});