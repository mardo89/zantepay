!function(e){var t={};function a(n){if(t[n])return t[n].exports;var s=t[n]={i:n,l:!1,exports:{}};return e[n].call(s.exports,s,s.exports,a),s.l=!0,s.exports}a.m=e,a.c=t,a.d=function(e,t,n){a.o(e,t)||Object.defineProperty(e,t,{configurable:!1,enumerable:!0,get:n})},a.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return a.d(t,"a",t),t},a.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},a.p="",a(a.s=61)}({1:function(e,t){window.getSpinner=function(e){return $("<div />").addClass("spinner spinner--"+e).append($("<div />")).append($("<div />")).append($("<div />")).append($("<div />"))},window.showSpinner=function(e){e.addClass("is-loading").prop("disabled",!0),e.append(getSpinner(30))},window.hideSpinner=function(e){e.removeClass("is-loading").prop("disabled",!1),e.find(".spinner").remove()},window.clearErrors=function(){$(".form-error").removeClass("form-error"),$(".error-text").remove()},window.showError=function(e){$.magnificPopup.open({items:{src:"#error-modal"},type:"inline",closeOnBgClick:!0,callbacks:{elementParse:function(t){$(t.src).find("#error-message").text(e)}}})},window.validateFile=function(e){var t=e.type.match(/(.png)|(.jpeg)|(.jpg)|(.pdf)$/i),a=e.size.toFixed(0)<4194304;return!(!t||!a)},window.scrollToError=function(){$("html, body").animate({scrollTop:$(".form-error:eq(0)").offset().top},500)}},61:function(e,t,a){e.exports=a(62)},62:function(e,t,a){a(1),$(document).ready(function(){$("#search_mail_events_frm").on("submit",function(e){e.preventDefault();var t=[];$(this).find('input[name="type-filter"]:checked').each(function(){t.push($(this).val())});var a=[];$(this).find('input[name="status-filter"]:checked').each(function(){a.push($(this).val())});var n=parseInt($(".page-item.active .page-link").html()),s=isNaN(n)?1:n,i=0,r="asc";$(".sort.sort-asc").length&&(i=$(".sort.sort-asc").index(),r="asc"),$(".sort.sort-desc").length&&(i=$(".sort.sort-desc").index(),r="desc");var o=$(this).find('button[type="submit"]');showSpinner(o),clearErrors(),$(".pagination").hide(),axios.get("/service/mail-events/search",{params:{type_filter:t,status_filter:a,page:s,sort_index:i,sort_order:r}}).then(function(e){hideSpinner(o),$("#events-list tbody").empty(),$(".pagination .page-item").empty().hide(),e.data.eventsList.forEach(function(e){$("#events-list tbody").append($("<tr />").attr("id",e.id).append($("<td />").html(e.date)).append($("<td />").addClass("col-center").html(e.event)).append($("<td />").html(e.to)).append($("<td />").html(e.status)).append($("<td />").append(e.isSuccess?"":$("<a />").addClass("send-link resend-email").attr("href","").html("Resend"))))});for(var t=1;t<=e.data.paginator.totalPages;t++){var a=e.data.paginator.currentPage==t?"page-item active":"page-item";$(".pagination").append($("<li />").addClass(a).append($("<a />").addClass("page-link").attr("href","#").html(t)))}e.data.paginator.totalPages>1&&($(".pagination").prepend($("<li />").addClass("page-item").append($("<a />").addClass("page-link prev-page-link").attr("href","#").html("Previous"))).append($("<li />").addClass("page-item").append($("<a />").addClass("page-link next-page-link").attr("href","#").html("Next"))),$(".pagination").show())}).catch(function(e){hideSpinner(o);var t=e.response.data.message;showError(t)})}),$(".pagination").on("click",".page-link",function(e){e.preventDefault();var t=$(".page-item.active"),a=$(this).parents(".page-item");return(!$(this).hasClass("prev-page-link")||!(a=(t=$(".page-item.active")).prev()).find(".prev-page-link").length)&&((!$(this).hasClass("next-page-link")||!(a=(t=$(".page-item.active")).next()).find(".next-page-link").length)&&(t.removeClass("active"),a.addClass("active"),void $("#search_mail_events_frm").trigger("submit")))}),$("#events-list .sort").on("click",function(e){e.preventDefault(),$(this).hasClass("sort-asc")?($(".sort").removeClass("sort-asc").removeClass("sort-desc"),$(this).addClass("sort-desc")):($(".sort").removeClass("sort-asc").removeClass("sort-desc"),$(this).addClass("sort-asc")),$("#search_mail_events_frm").trigger("submit")}),$('#search_mail_events_frm button[type="submit"]').on("click",function(e){e.preventDefault(),$(".pagination .page-item").empty(),$("#search_mail_events_frm").trigger("submit")}),$("#events-list").on("click",".resend-email",function(e){e.preventDefault();var t=$(this).parents("tr").attr("id"),a=$(this);a.hide(),axios.post("/service/mail-events/process",qs.stringify({id:t})).then(function(){a.remove()}).catch(function(e){a.show();var t=e.response.data.message;showError(t)})})})}});