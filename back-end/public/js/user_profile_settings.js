!function(e){var n={};function r(o){if(n[o])return n[o].exports;var t=n[o]={i:o,l:!1,exports:{}};return e[o].call(t.exports,t,t.exports,r),t.l=!0,t.exports}r.m=e,r.c=n,r.d=function(e,n,o){r.o(e,n)||Object.defineProperty(e,n,{configurable:!1,enumerable:!0,get:o})},r.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(n,"a",n),n},r.o=function(e,n){return Object.prototype.hasOwnProperty.call(e,n)},r.p="",r(r.s=45)}({1:function(e,n){window.getSpinner=function(e){return $("<div />").addClass("spinner spinner--"+e).append($("<div />")).append($("<div />")).append($("<div />")).append($("<div />"))},window.showSpinner=function(e){e.addClass("is-loading").prop("disabled",!0),e.append(getSpinner(30))},window.hideSpinner=function(e){e.removeClass("is-loading").prop("disabled",!1),e.find(".spinner").remove()},window.clearErrors=function(){$(".form-error").removeClass("form-error"),$(".error-text").remove()},window.showError=function(e){$.magnificPopup.open({items:{src:"#error-modal"},type:"inline",closeOnBgClick:!0,callbacks:{elementParse:function(n){$(n.src).find("#error-message").text(e)}}})},window.validateFile=function(e){var n=e.type.match(/(.png)|(.jpeg)|(.jpg)|(.pdf)$/i),r=e.size.toFixed(0)<4194304;return!(!n||!r)},window.scrollToError=function(){$("html, body").animate({scrollTop:$(".form-error:eq(0)").offset().top},500)}},45:function(e,n,r){e.exports=r(46)},46:function(e,n,r){r(1),$(document).ready(function(){$(".remove-document").on("click",function(e){e.preventDefault();var n={did:$(this).parents("li").attr("id")};axios.post("/user/profile-settings/remove-document",qs.stringify(n)).then(function(){$.magnificPopup.open({items:{src:"#remove-document-modal"},type:"inline",closeOnBgClick:!0,callbacks:{close:function(){window.location.reload()}}})}).catch(function(e){var n=e.response.data.message;showError(n)})}),$("#upload-identity-documents").on("submit",function(e){e.preventDefault();var n=new FormData,r=!0;if($.each($("#document-files")[0].files,function(e,o){if(!validateFile(o))return r=!1,!1;n.append("document_files[]",o)}),!r)return showError("Incorrect files format."),!1;var o=$("#upload-identity-documents").find('button[type="submit"]');showSpinner(o),axios.post("/user/profile-settings/upload-identity-documents",n).then(function(){hideSpinner(o),$.magnificPopup.open({items:{src:"#upload-documents-modal"},type:"inline",closeOnBgClick:!0,callbacks:{close:function(){window.location.reload()}}})}).catch(function(e){hideSpinner(o);var n=e.response.data.message;showError(n)})}),$("#upload-address-documents").on("submit",function(e){e.preventDefault(),clearErrors();var n=new FormData,r=!0;if($.each($("#address-files")[0].files,function(e,o){if(!validateFile(o))return r=!1,!1;n.append("address_files[]",o)}),!r)return $(".drag-drop-area").after($("<div />").addClass("error-text").text("Incorrect files format.")),!1;var o=$("#upload-identity-documents").find('button[type="submit"]');showSpinner(o),axios.post("/user/profile-settings/upload-address-documents",n).then(function(){hideSpinner(o),$.magnificPopup.open({items:{src:"#upload-documents-modal"},type:"inline",closeOnBgClick:!0,callbacks:{close:function(){window.location.reload()}}})}).catch(function(e){hideSpinner(o);var n=e.response.data,r=n.message,t=n.errors;422==e.response.status?$.each(t,function(e,n){$(".drag-drop-area").after($("<div />").addClass("error-text").text(n))}):showError(r)})}),$("#change-password").on("submit",function(e){e.preventDefault();var n=$(this).find('input[type="submit"]');showSpinner(n),clearErrors();var r={"current-password":$(this).find('input[name="current-password"]').val(),password:$(this).find('input[name="password"]').val(),password_confirmation:$(this).find('input[name="confirm-password"]').val()};axios.post("/user/profile-settings/change-password",qs.stringify(r)).then(function(){hideSpinner(n),$('#change-password input[type="password"]').val(""),$.magnificPopup.open({items:{src:"#change-password-modal"},type:"inline",closeOnBgClick:!0})}).catch(function(e){hideSpinner(n);var r=e.response.data,o=r.errors,t=r.message;422==e.response.status?($.each(o,function(e,n){$('#change-password input[name="'+e+'"]').parent().addClass("form-error"),$('#change-password input[name="'+e+'"]').after($("<span />").addClass("error-text").text(n))}),scrollToError()):showError(t)})}),$(".update-wallet").on("click",function(e){var n=this;if(e.preventDefault(),!$(this).parents(".wallet-address-group").find(".owner-confirm").prop("checked"))return showError("Please, confirm that you are the owner of this account"),!1;var r={currency:$(this).parents(".wallet-address-group").find('input[name="wallet-currency"]').val(),address:$(this).parents(".wallet-address-group").find('input[name="wallet-address"]').val()},o=$(this);showSpinner(o),clearErrors(),axios.post("/user/profile-settings/update-wallet",qs.stringify(r)).then(function(){hideSpinner(o),$(n).parents(".wallet-address-group").find(".owner-confirm").prop("checked",!1),$.magnificPopup.open({items:{src:"#wallet-address-modal"},type:"inline",closeOnBgClick:!0})}).catch(function(e){hideSpinner(o);var r=e.response.data.message;422==e.response.status?($(n).parents(".wallet-address-group").find('input[name="wallet-address"]').parent().addClass("form-error"),scrollToError()):showError(r)})})})}});