!function(e){var n={};function o(r){if(n[r])return n[r].exports;var t=n[r]={i:r,l:!1,exports:{}};return e[r].call(t.exports,t,t.exports,o),t.l=!0,t.exports}o.m=e,o.c=n,o.d=function(e,n,r){o.o(e,n)||Object.defineProperty(e,n,{configurable:!1,enumerable:!0,get:r})},o.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return o.d(n,"a",n),n},o.o=function(e,n){return Object.prototype.hasOwnProperty.call(e,n)},o.p="",o(o.s=58)}({1:function(e,n){window.getSpinner=function(e){return $("<div />").addClass("spinner spinner--"+e).append($("<div />")).append($("<div />")).append($("<div />")).append($("<div />"))},window.showSpinner=function(e){e.addClass("is-loading").prop("disabled",!0),e.append(getSpinner(30))},window.hideSpinner=function(e){e.removeClass("is-loading").prop("disabled",!1),e.find(".spinner").remove()},window.clearErrors=function(){$(".form-error").removeClass("form-error"),$(".error-text").remove()},window.showError=function(e){$.magnificPopup.open({items:{src:"#error-modal"},type:"inline",closeOnBgClick:!0,callbacks:{elementParse:function(n){$(n.src).find("#error-message").text(e)}}})},window.validateFile=function(e){var n=e.type.match(/(.png)|(.jpeg)|(.jpg)|(.pdf)$/i),o=e.size.toFixed(0)<4194304;return!(!n||!o)},window.scrollToError=function(){$("html, body").animate({scrollTop:$(".form-error:eq(0)").offset().top},500)},window.showConfirmation=function(e,n,o){$.magnificPopup.open({items:{src:"#confirmation-modal"},type:"inline",showCloseBtn:!1,closeOnBgClick:!1,callbacks:{elementParse:function(o){$(o.src).find("#confirmation-message").text(e),$(o.src).find("#accept_action").on("click",function(e){e.preventDefault(),$.magnificPopup.close(),"function"==typeof n&&n()}),$(o.src).find("#reject_action").on("click",function(e){e.preventDefault(),$.magnificPopup.close()})}}})},window.showPopover=function(e){$(".popover").remove();var n=$("<div />").addClass("popover").append($("<i />").addClass("fa fa-check-circle")).append($("<div />").addClass("popover__content").html(e)).append($("<a />").addClass("popover__close").attr("href","").html("Close").on("click",function(e){e.preventDefault(),$(".popover").remove()}));$("body").prepend(n),setTimeout(function(){n.remove()},5e3)}},58:function(e,n,o){e.exports=o(59)},59:function(e,n,o){o(1),$(document).ready(function(){$('select[name="user-role"]').on("change",function(e){e.preventDefault(),clearErrors();var n={uid:$("#user-profile-id").val(),role:$(this).val()};axios.post("/admin/profile",qs.stringify(n)).then(function(){$.magnificPopup.open({items:{src:"#save-profile-modal"},type:"inline",closeOnBgClick:!0})}).catch(function(e){var n=e.response.data,o=n.message,r=n.errors;422==e.response.status?($.each(r,function(e,n){$('#user-profile select[name="user-role"]').parents(".form-group").addClass("form-error"),$('#user-profile select[name="user-role"]').after($("<span />").addClass("error-text").text(n))}),scrollToError()):showError(o)})}),$("#remove-user").on("click",function(e){e.preventDefault();var n=$(this);showSpinner(n),clearErrors();var o={uid:$("#user-profile-id").val()};axios.post("/admin/profile/remove",qs.stringify(o)).then(function(){hideSpinner(n),$.magnificPopup.open({items:{src:"#remove-profile-modal"},type:"inline",closeOnBgClick:!0,callbacks:{close:function(){window.location="/admin/users"}}})}).catch(function(e){hideSpinner(n);var o=e.response.data.message;showError(o)})}),$(".approve-documents").on("click",function(e){var n=this;e.preventDefault();var o=$(this);showSpinner(o),clearErrors();var r={uid:$("#user-profile-id").val(),type:$(this).parent().find('input[name="document-type"]').val()};axios.post("document/approve",qs.stringify(r)).then(function(e){hideSpinner(o);var r=$(n).parents(".row");r.find(".document-actions").before($("<div />").addClass("col-md-3 col-sm-4 col-5 mb-20 document-status").html(e.data.status)),r.find(".document-actions").remove(),r.find(".document-reason").remove(),$.magnificPopup.open({items:{src:"#approve-documents-modal"},type:"inline",closeOnBgClick:!0})}).catch(function(e){hideSpinner(o);var n=e.response.data.message;showError(n)})}),$(".decline-documents").on("click",function(e){var n=this;e.preventDefault();var o=$(this);showSpinner(o),clearErrors();var r={uid:$("#user-profile-id").val(),type:$(this).parent().find('input[name="document-type"]').val(),reason:$(this).parents(".row").find('input[name="decline-reason"]').val()};axios.post("document/decline",qs.stringify(r)).then(function(e){hideSpinner(o);var r=$(n).parents(".row");r.find(".document-actions").before($("<div />").addClass("col-md-3 col-sm-4 col-5 mb-20 document-status").html(e.data.status)),r.find(".document-actions").remove(),r.find(".document-reason").remove(),$.magnificPopup.open({items:{src:"#decline-documents-modal"},type:"inline",closeOnBgClick:!0})}).catch(function(e){hideSpinner(o);var r=e.response.data,t=r.message,a=r.errors;422==e.response.status?($.each(a,function(e,o){$(n).parents(".row").find('input[name="decline-reason"]').parent().addClass("form-error"),$(n).parents(".row").find('input[name="decline-reason"]').after($("<span />").addClass("error-text").text(o))}),scrollToError()):showError(t)})}),$("#add-ico-znx").on("click",function(e){e.preventDefault();var n=$(this);showSpinner(n),clearErrors();var o={uid:$("#user-profile-id").val(),amount:$('.ico-pool input[name="znx-amount"]').val()};axios.post("/admin/wallet/add-ico-znx",qs.stringify(o)).then(function(e){hideSpinner(n),$('.ico-pool input[name="znx-amount"]').val(""),$("#total-znx-amount").html(e.data.totalAmount),$.magnificPopup.open({items:{src:"#add-ico-znx-modal"},type:"inline",closeOnBgClick:!0,callbacks:{elementParse:function(e){$(e.src).find(".znx_added").text(o.amount)}}})}).catch(function(e){hideSpinner(n);var o=e.response.data,r=o.message,t=o.errors;422==e.response.status?($.each(t,function(e,n){$('.ico-pool input[name="znx-amount"]').parent().addClass("form-error"),$('.ico-pool input[name="znx-amount"]').after($("<span />").addClass("error-text").text(n))}),scrollToError()):showError(r)})}),$("#add-foundation-znx").on("click",function(e){e.preventDefault();var n=$(this);showSpinner(n),clearErrors();var o={uid:$("#user-profile-id").val(),amount:$('.foundation-pool input[name="znx-amount"]').val()};axios.post("/admin/wallet/add-foundation-znx",qs.stringify(o)).then(function(e){hideSpinner(n),$('.foundation-pool input[name="znx-amount"]').val(""),$("#total-znx-amount").html(e.data.totalAmount),$.magnificPopup.open({items:{src:"#add-foundation-znx-modal"},type:"inline",closeOnBgClick:!0,callbacks:{elementParse:function(e){$(e.src).find(".znx_added").text(o.amount)}}})}).catch(function(e){hideSpinner(n);var o=e.response.data,r=o.message,t=o.errors;422==e.response.status?($.each(t,function(e,n){$('.foundation-pool input[name="znx-amount"]').parent().addClass("form-error"),$('.foundation-pool input[name="znx-amount"]').after($("<span />").addClass("error-text").text(n))}),scrollToError()):showError(r)})}),$(".update-wallet").on("click",function(e){var n=this;e.preventDefault();var o=$(this);showSpinner(o),clearErrors();var r={uid:$("#user-profile-id").val(),currency:$(this).parents(".wallet-address-group").find('input[name="wallet-currency"]').val(),address:$(this).parents(".wallet-address-group").find('input[name="wallet-address"]').val()};axios.post("/admin/wallet",qs.stringify(r)).then(function(){hideSpinner(o),$.magnificPopup.open({items:{src:"#wallet-address-modal"},type:"inline",closeOnBgClick:!0})}).catch(function(e){hideSpinner(o);var r=e.response.data,t=r.message,a=r.errors;422==e.response.status?($.each(a,function(e,o){$(n).parents(".wallet-address-group").find('input[name="wallet-address"]').parent().addClass("form-error"),$(n).parents(".wallet-address-group").find('input[name="wallet-address"]').after($("<span />").addClass("error-text").text(o))}),scrollToError()):showError(t)})})})}});