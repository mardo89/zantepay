!function(e){var n={};function r(o){if(n[o])return n[o].exports;var t=n[o]={i:o,l:!1,exports:{}};return e[o].call(t.exports,t,t.exports,r),t.l=!0,t.exports}r.m=e,r.c=n,r.d=function(e,n,o){r.o(e,n)||Object.defineProperty(e,n,{configurable:!1,enumerable:!0,get:o})},r.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(n,"a",n),n},r.o=function(e,n){return Object.prototype.hasOwnProperty.call(e,n)},r.p="",r(r.s=57)}({1:function(e,n){window.getSpinner=function(e){return $("<div />").addClass("spinner spinner--"+e).append($("<div />")).append($("<div />")).append($("<div />")).append($("<div />"))},window.showSpinner=function(e){e.addClass("is-loading").prop("disabled",!0),e.append(getSpinner(30))},window.hideSpinner=function(e){e.removeClass("is-loading").prop("disabled",!1),e.find(".spinner").remove()},window.clearErrors=function(){$(".form-error").removeClass("form-error"),$(".error-text").remove()},window.showError=function(e){$.magnificPopup.open({items:{src:"#error-modal"},type:"inline",closeOnBgClick:!0,callbacks:{elementParse:function(n){$(n.src).find("#error-message").text(e)}}})},window.validateFile=function(e){var n=e.type.match(/(.png)|(.jpeg)|(.jpg)|(.pdf)$/i),r=e.size.toFixed(0)<4194304;return!(!n||!r)},window.scrollToError=function(){$("html, body").animate({scrollTop:$(".form-error:eq(0)").offset().top},500)}},57:function(e,n,r){e.exports=r(58)},58:function(e,n,r){r(1),$(document).ready(function(){$('select[name="user-role"]').on("change",function(e){e.preventDefault(),clearErrors();var n={uid:$("#user-profile-id").val(),role:$(this).val()};axios.post("/admin/profile",qs.stringify(n)).then(function(){$.magnificPopup.open({items:{src:"#save-profile-modal"},type:"inline",closeOnBgClick:!0})}).catch(function(e){var n=e.response.data,r=n.message,o=n.errors;422==e.response.status?($.each(o,function(e,n){$('#user-profile select[name="user-role"]').parents(".form-group").addClass("form-error"),$('#user-profile select[name="user-role"]').after($("<span />").addClass("error-text").text(n))}),scrollToError()):showError(r)})}),$("#remove-user").on("click",function(e){e.preventDefault();var n=$(this);showSpinner(n),clearErrors();var r={uid:$("#user-profile-id").val()};axios.post("/admin/profile/remove",qs.stringify(r)).then(function(){hideSpinner(n),$.magnificPopup.open({items:{src:"#remove-profile-modal"},type:"inline",closeOnBgClick:!0,callbacks:{close:function(){window.location="/admin/users"}}})}).catch(function(e){hideSpinner(n);var r=e.response.data.message;showError(r)})}),$(".approve-documents").on("click",function(e){var n=this;e.preventDefault();var r=$(this);showSpinner(r),clearErrors();var o={uid:$("#user-profile-id").val(),type:$(this).parent().find('input[name="document-type"]').val()};axios.post("document/approve",qs.stringify(o)).then(function(e){hideSpinner(r);var o=$(n).parents(".row");o.find(".document-actions").before($("<div />").addClass("col-md-3 col-sm-4 col-5 mb-20 document-status").html(e.data.status)),o.find(".document-actions").remove(),o.find(".document-reason").remove(),$.magnificPopup.open({items:{src:"#approve-documents-modal"},type:"inline",closeOnBgClick:!0})}).catch(function(e){hideSpinner(r);var n=e.response.data.message;showError(n)})}),$(".decline-documents").on("click",function(e){var n=this;e.preventDefault();var r=$(this);showSpinner(r),clearErrors();var o={uid:$("#user-profile-id").val(),type:$(this).parent().find('input[name="document-type"]').val(),reason:$(this).parents(".row").find('input[name="decline-reason"]').val()};axios.post("document/decline",qs.stringify(o)).then(function(e){hideSpinner(r);var o=$(n).parents(".row");o.find(".document-actions").before($("<div />").addClass("col-md-3 col-sm-4 col-5 mb-20 document-status").html(e.data.status)),o.find(".document-actions").remove(),o.find(".document-reason").remove(),$.magnificPopup.open({items:{src:"#decline-documents-modal"},type:"inline",closeOnBgClick:!0})}).catch(function(e){hideSpinner(r);var o=e.response.data,t=o.message,a=o.errors;422==e.response.status?($.each(a,function(e,r){$(n).parents(".row").find('input[name="decline-reason"]').parent().addClass("form-error"),$(n).parents(".row").find('input[name="decline-reason"]').after($("<span />").addClass("error-text").text(r))}),scrollToError()):showError(t)})}),$("#add-ico-znx").on("click",function(e){e.preventDefault();var n=$(this);showSpinner(n),clearErrors();var r={uid:$("#user-profile-id").val(),amount:$('.ico-pool input[name="znx-amount"]').val()};axios.post("/admin/wallet/add-ico-znx",qs.stringify(r)).then(function(e){hideSpinner(n),$('.ico-pool input[name="znx-amount"]').val(""),$("#total-znx-amount").html(e.data.totalAmount),$.magnificPopup.open({items:{src:"#add-ico-znx-modal"},type:"inline",closeOnBgClick:!0,callbacks:{elementParse:function(e){$(e.src).find(".znx_added").text(r.amount)}}})}).catch(function(e){hideSpinner(n);var r=e.response.data,o=r.message,t=r.errors;422==e.response.status?($.each(t,function(e,n){$('.ico-pool input[name="znx-amount"]').parent().addClass("form-error"),$('.ico-pool input[name="znx-amount"]').after($("<span />").addClass("error-text").text(n))}),scrollToError()):showError(o)})}),$("#add-foundation-znx").on("click",function(e){e.preventDefault();var n=$(this);showSpinner(n),clearErrors();var r={uid:$("#user-profile-id").val(),amount:$('.foundation-pool input[name="znx-amount"]').val()};axios.post("/admin/wallet/add-foundation-znx",qs.stringify(r)).then(function(e){hideSpinner(n),$('.foundation-pool input[name="znx-amount"]').val(""),$("#total-znx-amount").html(e.data.totalAmount),$.magnificPopup.open({items:{src:"#add-foundation-znx-modal"},type:"inline",closeOnBgClick:!0,callbacks:{elementParse:function(e){$(e.src).find(".znx_added").text(r.amount)}}})}).catch(function(e){hideSpinner(n);var r=e.response.data,o=r.message,t=r.errors;422==e.response.status?($.each(t,function(e,n){$('.foundation-pool input[name="znx-amount"]').parent().addClass("form-error"),$('.foundation-pool input[name="znx-amount"]').after($("<span />").addClass("error-text").text(n))}),scrollToError()):showError(o)})}),$(".update-wallet").on("click",function(e){var n=this;e.preventDefault();var r=$(this);showSpinner(r),clearErrors();var o={uid:$("#user-profile-id").val(),currency:$(this).parents(".wallet-address-group").find('input[name="wallet-currency"]').val(),address:$(this).parents(".wallet-address-group").find('input[name="wallet-address"]').val()};axios.post("/admin/wallet",qs.stringify(o)).then(function(){hideSpinner(r),$.magnificPopup.open({items:{src:"#wallet-address-modal"},type:"inline",closeOnBgClick:!0})}).catch(function(e){hideSpinner(r);var o=e.response.data,t=o.message,a=o.errors;422==e.response.status?($.each(a,function(e,r){$(n).parents(".wallet-address-group").find('input[name="wallet-address"]').parent().addClass("form-error"),$(n).parents(".wallet-address-group").find('input[name="wallet-address"]').after($("<span />").addClass("error-text").text(r))}),scrollToError()):showError(t)})})})}});