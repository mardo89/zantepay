!function(e){var t={};function n(i){if(t[i])return t[i].exports;var a=t[i]={i:i,l:!1,exports:{}};return e[i].call(a.exports,a,a.exports,n),a.l=!0,a.exports}n.m=e,n.c=t,n.d=function(e,t,i){n.o(e,t)||Object.defineProperty(e,t,{configurable:!1,enumerable:!0,get:i})},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=64)}({1:function(e,t){var n=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var i in n)Object.prototype.hasOwnProperty.call(n,i)&&(e[i]=n[i])}return e};window.getSpinner=function(e){return $("<div />").addClass("spinner spinner--"+e).append($("<div />")).append($("<div />")).append($("<div />")).append($("<div />"))},window.showSpinner=function(e){e.addClass("is-loading").prop("disabled",!0),e.append(getSpinner(30))},window.hideSpinner=function(e){e.removeClass("is-loading").prop("disabled",!1),e.find(".spinner").remove()},window.clearErrors=function(){$(".form-error").removeClass("form-error"),$(".error-text").remove()},window.showError=function(e){$.magnificPopup.open({items:{src:"#error-modal"},type:"inline",closeOnBgClick:!0,callbacks:{elementParse:function(t){$(t.src).find("#error-message").text(e)}}})},window.validateFile=function(e){var t=e.type.match(/(.png)|(.jpeg)|(.jpg)|(.pdf)$/i),n=e.size.toFixed(0)<4194304;return!(!t||!n)},window.scrollToError=function(){$("html, body").animate({scrollTop:$(".form-error:eq(0)").offset().top},500)},window.showConfirmation=function(e,t,n){$.magnificPopup.open({items:{src:"#confirmation-modal"},type:"inline",showCloseBtn:!1,closeOnBgClick:!1,callbacks:{elementParse:function(n){$(n.src).find("#confirmation-message").text(e),$(n.src).find("#accept_action").off("click").on("click",function(e){e.preventDefault(),$.magnificPopup.close(),"function"==typeof t&&t()}),$(n.src).find("#reject_action").off("click").on("click",function(e){e.preventDefault(),$.magnificPopup.close()})}}})},window.showPopover=function(e){$(".popover").remove();var t=$("<div />").addClass("popover").append($("<i />").addClass("fa fa-check-circle")).append($("<div />").addClass("popover__content").html(e)).append($("<a />").addClass("popover__close").attr("href","").html("Close").on("click",function(e){e.preventDefault(),$(".popover").remove()}));$("body").prepend(t),setTimeout(function(){t.remove()},5e3)},window.showProtectionDialog=function(e){$.magnificPopup.open({items:{src:"#protection-modal"},type:"inline",showCloseBtn:!0,closeOnBgClick:!0,callbacks:{elementParse:function(t){$(t.src).find("#frm_protection").find('input[name="signature"]').val(""),$(t.src).find("#frm_protection").off("submit").on("submit",function(t){t.preventDefault(),sessionStorage.setItem("signature",$(this).find('input[name="signature"]').val()),$.magnificPopup.close(),e()})}}})},window.processProtectionRequest=function(e,t){var i=sessionStorage.getItem("signature");if(sessionStorage.removeItem("signature"),!i){var a=(new Date).valueOf();return sessionStorage.setItem("action_timestamp",a),n({},t,{action:e,action_timestamp:a})}return n({},t,{action_timestamp:sessionStorage.getItem("action_timestamp"),signature:i})},window.processProtectionResponse=function(e,t,n){205==e?"function"==typeof t&&showProtectionDialog(t):"function"==typeof n&&n()}},64:function(e,t,n){e.exports=n(65)},65:function(e,t,n){n(1),$(document).ready(function(){$("#search_mail_events_frm").on("submit",function(e){e.preventDefault();var t=[];$(this).find('input[name="type-filter"]:checked').each(function(){t.push($(this).val())});var n=[];$(this).find('input[name="status-filter"]:checked').each(function(){n.push($(this).val())});var i=parseInt($(".page-item.active .page-link").html()),a=isNaN(i)?1:i,s=0,o="desc";$(".sort.sort-asc").length&&(s=$(".sort.sort-asc").index(),o="asc"),$(".sort.sort-desc").length&&(s=$(".sort.sort-desc").index(),o="desc");var r=$(this).find('button[type="submit"]');showSpinner(r),clearErrors(),$(".pagination").hide(),axios.get("/service/mail-events/search",{params:{type_filter:t,status_filter:n,page:a,sort_index:s,sort_order:o}}).then(function(e){hideSpinner(r),$("#events-list tbody").empty(),$(".pagination .page-item").empty().hide(),e.data.eventsList.forEach(function(e){$("#events-list tbody").append($("<tr />").attr("id",e.id).append($("<td />").html(e.date)).append($("<td />").addClass("col-center").html(e.event)).append($("<td />").html(e.to)).append($("<td />").html(e.status)).append($("<td />").append(e.isSuccess?"":$("<a />").addClass("send-link resend-email").attr("href","").html("Resend"))))});for(var t=1;t<=e.data.paginator.totalPages;t++){var n=e.data.paginator.currentPage==t?"page-item active":"page-item";$(".pagination").append($("<li />").addClass(n).append($("<a />").addClass("page-link").attr("href","#").html(t)))}e.data.paginator.totalPages>1&&($(".pagination").prepend($("<li />").addClass("page-item").append($("<a />").addClass("page-link prev-page-link").attr("href","#").html("Previous"))).append($("<li />").addClass("page-item").append($("<a />").addClass("page-link next-page-link").attr("href","#").html("Next"))),$(".pagination").show())}).catch(function(e){hideSpinner(r);var t=e.response.data.message;showError(t)})}),$(".pagination").on("click",".page-link",function(e){e.preventDefault();var t=$(".page-item.active"),n=$(this).parents(".page-item");return(!$(this).hasClass("prev-page-link")||!(n=(t=$(".page-item.active")).prev()).find(".prev-page-link").length)&&((!$(this).hasClass("next-page-link")||!(n=(t=$(".page-item.active")).next()).find(".next-page-link").length)&&(t.removeClass("active"),n.addClass("active"),void $("#search_mail_events_frm").trigger("submit")))}),$("#events-list .sort").on("click",function(e){e.preventDefault(),$(this).hasClass("sort-asc")?($(".sort").removeClass("sort-asc").removeClass("sort-desc"),$(this).addClass("sort-desc")):($(".sort").removeClass("sort-asc").removeClass("sort-desc"),$(this).addClass("sort-asc")),$("#search_mail_events_frm").trigger("submit")}),$('#search_mail_events_frm button[type="submit"]').on("click",function(e){e.preventDefault(),$(".pagination .page-item").empty(),$("#search_mail_events_frm").trigger("submit")}),$("#events-list").on("click",".resend-email",function(e){e.preventDefault();var t=$(this).parents("tr").attr("id"),n=$(this);n.hide(),axios.post("/service/mail-events/process",qs.stringify({id:t})).then(function(){n.remove()}).catch(function(e){n.show();var t=e.response.data.message;showError(t)})})})}});