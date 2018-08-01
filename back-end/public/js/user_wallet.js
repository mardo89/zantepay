!function(e){var n={};function t(r){if(n[r])return n[r].exports;var o=n[r]={i:r,l:!1,exports:{}};return e[r].call(o.exports,o,o.exports,t),o.l=!0,o.exports}t.m=e,t.c=n,t.d=function(e,n,r){t.o(e,n)||Object.defineProperty(e,n,{configurable:!1,enumerable:!0,get:r})},t.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(n,"a",n),n},t.o=function(e,n){return Object.prototype.hasOwnProperty.call(e,n)},t.p="",t(t.s=52)}({1:function(e,n){var t=Object.assign||function(e){for(var n=1;n<arguments.length;n++){var t=arguments[n];for(var r in t)Object.prototype.hasOwnProperty.call(t,r)&&(e[r]=t[r])}return e};window.getSpinner=function(e){return $("<div />").addClass("spinner spinner--"+e).append($("<div />")).append($("<div />")).append($("<div />")).append($("<div />"))},window.showSpinner=function(e){e.addClass("is-loading").prop("disabled",!0),e.append(getSpinner(30))},window.hideSpinner=function(e){e.removeClass("is-loading").prop("disabled",!1),e.find(".spinner").remove()},window.clearErrors=function(){$(".form-error").removeClass("form-error"),$(".error-text").remove()},window.showError=function(e){$.magnificPopup.open({items:{src:"#error-modal"},type:"inline",closeOnBgClick:!0,callbacks:{elementParse:function(n){$(n.src).find("#error-message").text(e)}}})},window.validateFile=function(e){var n=e.type.match(/(.png)|(.jpeg)|(.jpg)|(.pdf)$/i),t=e.size.toFixed(0)<4194304;return!(!n||!t)},window.scrollToError=function(){$("html, body").animate({scrollTop:$(".form-error:eq(0)").offset().top},500)},window.showConfirmation=function(e,n,t){$.magnificPopup.open({items:{src:"#confirmation-modal"},type:"inline",showCloseBtn:!1,closeOnBgClick:!1,callbacks:{elementParse:function(t){$(t.src).find("#confirmation-message").text(e),$(t.src).find("#accept_action").off("click").on("click",function(e){e.preventDefault(),$.magnificPopup.close(),"function"==typeof n&&n()}),$(t.src).find("#reject_action").off("click").on("click",function(e){e.preventDefault(),$.magnificPopup.close()})}}})},window.showPopover=function(e){$(".popover").remove();var n=$("<div />").addClass("popover").append($("<i />").addClass("fa fa-check-circle")).append($("<div />").addClass("popover__content").html(e)).append($("<a />").addClass("popover__close").attr("href","").html("Close").on("click",function(e){e.preventDefault(),$(".popover").remove()}));$("body").prepend(n),setTimeout(function(){n.remove()},5e3)},window.showProtectionDialog=function(e){$.magnificPopup.open({items:{src:"#protection-modal"},type:"inline",showCloseBtn:!0,closeOnBgClick:!0,callbacks:{elementParse:function(n){$(n.src).find("#frm_protection").find('input[name="signature"]').val(""),$(n.src).find("#frm_protection").off("submit").on("submit",function(n){n.preventDefault(),sessionStorage.setItem("signature",$(this).find('input[name="signature"]').val()),$.magnificPopup.close(),e()})}}})},window.processProtectionRequest=function(e,n){var r=sessionStorage.getItem("signature");if(sessionStorage.removeItem("signature"),!r){var o=(new Date).valueOf();return sessionStorage.setItem("action_timestamp",o),t({},n,{action:e,action_timestamp:o})}return t({},n,{action_timestamp:sessionStorage.getItem("action_timestamp"),signature:r})},window.processProtectionResponse=function(e,n,t){205==e?"function"==typeof n&&showProtectionDialog(n):"function"==typeof t&&t()}},52:function(e,n,t){e.exports=t(53)},53:function(e,n,t){t(1);$(document).ready(function(){$(".wallet").on("click","#copy-address",function(e){e.preventDefault();var n=$(this).parents(".wallet").find(".address").text(),t=$("<input />").val(n);$(this).after(t),t.focus(),t.get(0).setSelectionRange(0,n.length),document.execCommand("copy"),t.remove(),showPopover("Address copied to clipboard")}),$(".create-address").on("click",function(e){var n=this;e.preventDefault();var t=$(this);showSpinner(t),clearErrors(),t.parent().after($("<div />").addClass("col col-md-12 mt-20 primary-color text-sm address-warning").append($("<span />").text("This operation can take up to 5 minutes. Please do not close or refresh this page."))),axios.post("/user/wallet/address",qs.stringify({})).then(function(e){hideSpinner(t),$(".address-warning").remove();var n=t.parent();n.before($("<div />").addClass("col col-sm-auto text-lg wordwrap address").text(e.data.address)).before($("<div />").addClass("col col-md-3").append($("<a />").addClass("btn btn--shadowed-light btn--medium btn--130 mt-sm-15").attr({id:"copy-address",href:""}).text("Copy"))),n.remove(),$.magnificPopup.open({items:{src:"#wallet-address-modal"},type:"inline",closeOnBgClick:!0})}).catch(function(e){hideSpinner(t),$(".address-warning").remove();var r=e.response.data.message;422==e.response.status?($(n).parents(".wallet-address-group").find('input[name="wallet-address"]').parent().addClass("form-error"),scrollToError()):showError(r)})}),$('.rate-calculator input[type="text"]').on("focus",function(e){clearErrors();var n=$(this).attr("name"),t=$('.rate-calculator input[name!="'+n+'"]').attr("name");$('input[name="'+n+'"]').val(""),$('input[name="'+t+'"]').val("")}),$('.rate-calculator input[type="text"]').on("keyup",function(e){var n=$(this).attr("name"),t=$('.rate-calculator input[name!="'+n+'"]').attr("name");if(0!==$(this).val().length){var r={};r[n]=$(this).val(),axios.post("/user/wallet/rate-calculator",qs.stringify(r)).then(function(e){clearErrors(),$('input[name="'+t+'"]').val(e.data.balance)}).catch(function(e){var n=e.response.data,t=n.errors,r=n.message;422==e.response.status?$.each(t,function(e,n){$('.rate-calculator input[name="'+e+'"]').parent().addClass("form-error")}):showError(r)})}else $('input[name="'+t+'"]').val("")}),$("#transfer_btn").on("click",function(e){var n=this;e.preventDefault(),showConfirmation("Are you sure you want to transfer ETH to ZNX?",function(){!function e(n){var t=n;showSpinner(t),clearErrors();var r=processProtectionRequest("Transfer ETH to ZNX",{eth_amount:$('input[name="transfer_eth_amount"]').val()});axios.post("/user/wallet/transfer-eth",qs.stringify(r)).then(function(r){hideSpinner(t),processProtectionResponse(r.status,function(){e(n)},function(){$('input[name="transfer_eth_amount"]').val(""),$("#available_znx_amount").text(r.data.total),$.magnificPopup.open({items:{src:"#transfer-modal"},type:"inline",closeOnBgClick:!0,callbacks:{close:function(){window.location.reload()},elementParse:function(e){$(e.src).find("#znx_balance").text(r.data.balance)}}})})}).catch(function(e){hideSpinner(t);var n=e.response.data,r=n.message,o=n.errors;422==e.response.status?($.each(o,function(e,n){$('input[name="transfer_'+e+'"]').parent().addClass("form-error"),$('input[name="transfer_'+e+'"]').after($("<span />").addClass("error-text").text(n))}),scrollToError()):showError(r)})}($(n))})}),$("#withdraw_btn").on("click",function(e){var n=this;e.preventDefault(),showConfirmation("Are you sure you want to withdraw ETH?",function(){!function e(n){var t=n;showSpinner(t),clearErrors();var r=processProtectionRequest("Withdraw ETH",{address:$('input[name="withdraw_address"]').val()});axios.post("/user/wallet/withdraw-eth",qs.stringify(r)).then(function(r){hideSpinner(t),processProtectionResponse(r.status,function(){e(n)},function(){$('input[name="withdraw_address"]').val(""),$.magnificPopup.open({items:{src:"#withdraw-modal"},type:"inline",closeOnBgClick:!0,callbacks:{close:function(){window.location.reload()}}})})}).catch(function(e){hideSpinner(t);var n=e.response.data,r=n.message,o=n.errors;422==e.response.status?($.each(o,function(e,n){$('input[name="withdraw_'+e+'"]').parent().addClass("form-error"),$('input[name="withdraw_'+e+'"]').after($("<span />").addClass("error-text").text(n))}),scrollToError()):showError(r)})}($(n))})}),$("#frm_welcome").on("submit",function(e){if(e.preventDefault(),clearErrors(),$('#welcome input[name="tc_item"]').length!==$('#welcome input[name="tc_item"]:checked').length)return $('#welcome input[name="tc_item"]').each(function(){$(this).prop("checked")||$(this).parents(".logon-group").addClass("form-error")}),!1;var n=$("<div />").addClass("spinner-container").css("height","50px").append($("<div />").addClass("spinner spinner--50").append($("<div />")).append($("<div />")).append($("<div />")).append($("<div />"))),t=$(this).find('input[type="submit"]');t.prop("disabled",!0).hide(),t.after(n);var r={to_newsletters:$('#welcome input[name="to_newsletters"]:checked').length};axios.post("/user/accept-terms",qs.stringify(r)).then(function(){window.location.reload()}).catch(function(e){t.prop("disabled",!1).show(),n.remove();var r=e.response.data.message;$(".logon-group").last().after($("<span />").addClass("error-text").text(r))})})})}});