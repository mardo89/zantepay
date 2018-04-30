!function(e){var n={};function t(r){if(n[r])return n[r].exports;var o=n[r]={i:r,l:!1,exports:{}};return e[r].call(o.exports,o,o.exports,t),o.l=!0,o.exports}t.m=e,t.c=n,t.d=function(e,n,r){t.o(e,n)||Object.defineProperty(e,n,{configurable:!1,enumerable:!0,get:r})},t.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(n,"a",n),n},t.o=function(e,n){return Object.prototype.hasOwnProperty.call(e,n)},t.p="",t(t.s=54)}({1:function(e,n){var t=Object.assign||function(e){for(var n=1;n<arguments.length;n++){var t=arguments[n];for(var r in t)Object.prototype.hasOwnProperty.call(t,r)&&(e[r]=t[r])}return e};window.getSpinner=function(e){return $("<div />").addClass("spinner spinner--"+e).append($("<div />")).append($("<div />")).append($("<div />")).append($("<div />"))},window.showSpinner=function(e){e.addClass("is-loading").prop("disabled",!0),e.append(getSpinner(30))},window.hideSpinner=function(e){e.removeClass("is-loading").prop("disabled",!1),e.find(".spinner").remove()},window.clearErrors=function(){$(".form-error").removeClass("form-error"),$(".error-text").remove()},window.showError=function(e){$.magnificPopup.open({items:{src:"#error-modal"},type:"inline",closeOnBgClick:!0,callbacks:{elementParse:function(n){$(n.src).find("#error-message").text(e)}}})},window.validateFile=function(e){var n=e.type.match(/(.png)|(.jpeg)|(.jpg)|(.pdf)$/i),t=e.size.toFixed(0)<4194304;return!(!n||!t)},window.scrollToError=function(){$("html, body").animate({scrollTop:$(".form-error:eq(0)").offset().top},500)},window.showConfirmation=function(e,n,t){$.magnificPopup.open({items:{src:"#confirmation-modal"},type:"inline",showCloseBtn:!1,closeOnBgClick:!1,callbacks:{elementParse:function(t){$(t.src).find("#confirmation-message").text(e),$(t.src).find("#accept_action").on("click",function(e){e.preventDefault(),$.magnificPopup.close(),"function"==typeof n&&n()}),$(t.src).find("#reject_action").on("click",function(e){e.preventDefault(),$.magnificPopup.close()})}}})},window.showPopover=function(e){$(".popover").remove();var n=$("<div />").addClass("popover").append($("<i />").addClass("fa fa-check-circle")).append($("<div />").addClass("popover__content").html(e)).append($("<a />").addClass("popover__close").attr("href","").html("Close").on("click",function(e){e.preventDefault(),$(".popover").remove()}));$("body").prepend(n),setTimeout(function(){n.remove()},5e3)},window.showProtectionDialog=function(e){$.magnificPopup.open({items:{src:"#protection-modal"},type:"inline",showCloseBtn:!0,closeOnBgClick:!0,callbacks:{elementParse:function(n){$(n.src).find("#frm_protection").find('input[name="signature"]').val(""),$(n.src).find("#frm_protection").off("submit").on("submit",function(n){n.preventDefault(),sessionStorage.setItem("signature",$(this).find('input[name="signature"]').val()),$.magnificPopup.close(),e()})}}})},window.processProtectionRequest=function(e,n){var r=sessionStorage.getItem("signature");if(sessionStorage.removeItem("signature"),!r){var o=(new Date).valueOf();return sessionStorage.setItem("action_timestamp",o),t({},n,{action:e,action_timestamp:o})}return t({},n,{action_timestamp:sessionStorage.getItem("action_timestamp"),signature:r})},window.processProtectionResponse=function(e,n,t){205==e?"function"==typeof n&&showProtectionDialog(n):"function"==typeof t&&t()}},54:function(e,n,t){e.exports=t(55)},55:function(e,n,t){t(1),$(document).ready(function(){$("#dc_design").on("submit",function(e){if(e.preventDefault(),!$('input[name="terms"]').prop("checked"))return showError("Please, confirm that you have read debit card pre-order Terms & Conditions"),!1;var n=$("#dc_design").find('button[type="submit"]');showSpinner(n);var t={design:$('input[name="card-type"]:checked').val()};axios.post("/user/debit-card",qs.stringify(t)).then(function(e){hideSpinner(n),window.location=e.data.nextStep}).catch(function(e){hideSpinner(n);var t=e.response.data.message;showError(t)})}),$("#dc_documents").on("submit",function(e){e.preventDefault(),clearErrors();var n=new FormData;if($('input[name="confirm"]').prop("checked"))n.append("verify_later",1);else{n.append("verify_later",0);var t=!0;if($.each($("#document-files")[0].files,function(e,r){if(!validateFile(r))return t=!1,!1;n.append("document_files[]",r)}),!t)return $(".drag-drop-area").after($("<div />").addClass("error-text").text("Incorrect files format.")),!1}var r=$("#dc_documents").find('button[type="submit"]');showSpinner(r),axios.post("/user/debit-card-documents",n).then(function(e){hideSpinner(r),window.location=e.data.nextStep}).catch(function(e){hideSpinner(r);var n=e.response.data,t=n.message,o=n.errors;422==e.response.status?$.each(o,function(e,n){$(".drag-drop-area").after($("<div />").addClass("error-text").text(n))}):showError(t)})}),$("#dc_address").on("submit",function(e){e.preventDefault(),clearErrors();var n=new FormData;if($('input[name="confirm"]').prop("checked"))n.append("verify_later",1);else{n.append("verify_later",0);var t=!0;if($.each($("#address-files")[0].files,function(e,r){if(!validateFile(r))return t=!1,!1;n.append("address_files[]",r)}),!t)return $(".drag-drop-area").after($("<div />").addClass("error-text").text("Incorrect files format.")),!1}var r=$("#dc_address").find('button[type="submit"]');showSpinner(r),axios.post("/user/debit-card-address",n).then(function(e){hideSpinner(r),window.location=e.data.nextStep}).catch(function(e){hideSpinner(r);var n=e.response.data,t=n.message,o=n.errors;422==e.response.status?$.each(o,function(e,n){$(".drag-drop-area").after($("<div />").addClass("error-text").text(n))}):showError(t)})})})}});