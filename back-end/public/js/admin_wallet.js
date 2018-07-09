!function(t){var a={};function n(e){if(a[e])return a[e].exports;var i=a[e]={i:e,l:!1,exports:{}};return t[e].call(i.exports,i,i.exports,n),i.l=!0,i.exports}n.m=t,n.c=a,n.d=function(t,a,e){n.o(t,a)||Object.defineProperty(t,a,{configurable:!1,enumerable:!0,get:e})},n.n=function(t){var a=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(a,"a",a),a},n.o=function(t,a){return Object.prototype.hasOwnProperty.call(t,a)},n.p="",n(n.s=62)}({1:function(t,a){var n=Object.assign||function(t){for(var a=1;a<arguments.length;a++){var n=arguments[a];for(var e in n)Object.prototype.hasOwnProperty.call(n,e)&&(t[e]=n[e])}return t};window.getSpinner=function(t){return $("<div />").addClass("spinner spinner--"+t).append($("<div />")).append($("<div />")).append($("<div />")).append($("<div />"))},window.showSpinner=function(t){t.addClass("is-loading").prop("disabled",!0),t.append(getSpinner(30))},window.hideSpinner=function(t){t.removeClass("is-loading").prop("disabled",!1),t.find(".spinner").remove()},window.clearErrors=function(){$(".form-error").removeClass("form-error"),$(".error-text").remove()},window.showError=function(t){$.magnificPopup.open({items:{src:"#error-modal"},type:"inline",closeOnBgClick:!0,callbacks:{elementParse:function(a){$(a.src).find("#error-message").text(t)}}})},window.validateFile=function(t){var a=t.type.match(/(.png)|(.jpeg)|(.jpg)|(.pdf)$/i),n=t.size.toFixed(0)<4194304;return!(!a||!n)},window.scrollToError=function(){$("html, body").animate({scrollTop:$(".form-error:eq(0)").offset().top},500)},window.showConfirmation=function(t,a,n){$.magnificPopup.open({items:{src:"#confirmation-modal"},type:"inline",showCloseBtn:!1,closeOnBgClick:!1,callbacks:{elementParse:function(n){$(n.src).find("#confirmation-message").text(t),$(n.src).find("#accept_action").off("click").on("click",function(t){t.preventDefault(),$.magnificPopup.close(),"function"==typeof a&&a()}),$(n.src).find("#reject_action").off("click").on("click",function(t){t.preventDefault(),$.magnificPopup.close()})}}})},window.showPopover=function(t){$(".popover").remove();var a=$("<div />").addClass("popover").append($("<i />").addClass("fa fa-check-circle")).append($("<div />").addClass("popover__content").html(t)).append($("<a />").addClass("popover__close").attr("href","").html("Close").on("click",function(t){t.preventDefault(),$(".popover").remove()}));$("body").prepend(a),setTimeout(function(){a.remove()},5e3)},window.showProtectionDialog=function(t){$.magnificPopup.open({items:{src:"#protection-modal"},type:"inline",showCloseBtn:!0,closeOnBgClick:!0,callbacks:{elementParse:function(a){$(a.src).find("#frm_protection").find('input[name="signature"]').val(""),$(a.src).find("#frm_protection").off("submit").on("submit",function(a){a.preventDefault(),sessionStorage.setItem("signature",$(this).find('input[name="signature"]').val()),$.magnificPopup.close(),t()})}}})},window.processProtectionRequest=function(t,a){var e=sessionStorage.getItem("signature");if(sessionStorage.removeItem("signature"),!e){var i=(new Date).valueOf();return sessionStorage.setItem("action_timestamp",i),n({},a,{action:t,action_timestamp:i})}return n({},a,{action_timestamp:sessionStorage.getItem("action_timestamp"),signature:e})},window.processProtectionResponse=function(t,a,n){205==t?"function"==typeof a&&showProtectionDialog(a):"function"==typeof n&&n()}},62:function(t,a,n){t.exports=n(63)},63:function(t,a,n){n(1);var e=function(t,a,n,e,i){var s="ICO_TOTAL"!==a?a:"",r=n.next("nav").find(".pagination"),o=parseInt(r.find(".page-item.active .page-link").html()),c=isNaN(o)?1:o,p=0,l="asc";n.find(".sort.sort-asc").length&&(p=$(".sort.sort-asc").index(),l="asc"),n.find(".sort.sort-desc").length&&(p=$(".sort.sort-desc").index(),l="desc"),n.find("tbody").empty(),n.find("tbody").append($("<tr />").addClass("operation-in-progress").append($("<td />").attr("colspan",4).append($("<a />").attr("href","").addClass("update-icon is-active")))),r.find(".page-item").empty(),axios.get(e,{params:{part_filter:s,status_filter:[],page:c,sort_index:p,sort_order:l}}).then(function(t){n.find(".operation-in-progress").remove(),t.data.transactionsList.forEach(function(t){var a="---";i&&(a=""===t.status?'<button class="btn btn--medium btn--shadowed-light grant_ico_coins" type="button">Issue Token</button>':t.status),n.find("tbody").append($("<tr />").data("uid",t.uid).append($("<td />").html(t.user)).append($("<td />").html(t.address)).append($("<td />").html(t.amount)).append($("<td />").html(a)))});for(var a=1;a<=t.data.paginator.totalPages;a++){var e=t.data.paginator.currentPage==a?"page-item active":"page-item";r.append($("<li />").addClass(e).append($("<a />").addClass("page-link").attr("href","#").html(a)))}t.data.paginator.totalPages>1&&r.prepend($("<li />").addClass("page-item").append($("<a />").addClass("page-link prev-page-link").attr("href","#").html("Previous"))).append($("<li />").addClass("page-item").append($("<a />").addClass("page-link next-page-link").attr("href","#").html("Next")))}).catch(function(t){n.find(".operation-in-progress").remove();var a=t.response.data.message;showError(a)})};$(document).ready(function(){$(document).on("click",".grant_ico_coins",function(t){t.preventDefault();var a=$(this);showSpinner(a),clearErrors();var n=$(this).parents("tr"),e={uid:n.data("uid"),address:n.find("td:eq(1)").text().trim(),amount:n.find("td:eq(2)").text().trim()};axios.post("/admin/wallet/grant-ico-coins",qs.stringify(e)).then(function(){hideSpinner(a),$.magnificPopup.open({items:{src:"#grant-coins-modal"},type:"inline",closeOnBgClick:!0})}).catch(function(t){hideSpinner(a);var n=t.response.data.message;showError(n)})}),$("#grant_marketing_coins").on("click",function(t){t.preventDefault();var a=$(this);showSpinner(a),clearErrors();var n={address:$("#grant_marketing_address").val(),amount:$("#grant_marketing_amount").val()};axios.post("/admin/wallet/grant-marketing-coins",qs.stringify(n)).then(function(){hideSpinner(a),$.magnificPopup.open({items:{src:"#grant-coins-modal"},type:"inline",closeOnBgClick:!0})}).catch(function(t){hideSpinner(a);var n=t.response.data.message;showError(n)})}),$("#grant_company_coins").on("click",function(t){t.preventDefault();var a=$(this);showSpinner(a),clearErrors();var n={address:$("#grant_company_address").val(),amount:$("#grant_company_amount").val()};axios.post("/admin/wallet/grant-company-coins",qs.stringify(n)).then(function(){hideSpinner(a),$.magnificPopup.open({items:{src:"#grant-coins-modal"},type:"inline",closeOnBgClick:!0})}).catch(function(t){hideSpinner(a);var n=t.response.data.message;showError(n)})}),$("#ico_part_filter li").each(function(){var t=$(this).attr("id"),a=$(this).find("a").attr("href"),n=$(a).find("table");e(0,t,n,"/admin/wallet/search-ico-transactions","#total"===a)}),$(".ico_transactions_block .pagination").on("click",".page-link",function(t){t.preventDefault();var a=$(this).parents(".pagination"),n=a.find(".page-item.active"),i=$(this).parents(".page-item");if($(this).hasClass("prev-page-link")&&(i=(n=a.find(".page-item.active")).prev()).find(".prev-page-link").length)return!1;if($(this).hasClass("next-page-link")&&(i=(n=a.find(".page-item.active")).next()).find(".next-page-link").length)return!1;n.removeClass("active"),i.addClass("active");var s=$(this).parents(".ico_transactions_block").find("table"),r=$(this).parents(".ico_transactions_block").attr("id"),o=$("#ico_part_filter").find('a[href="#'+r+'"]').parent().attr("id");e(0,o,s,"/admin/wallet/search-ico-transactions","total"===r)}),$(".ico_transactions_block  .sort").on("click",function(t){t.preventDefault(),$(this).hasClass("sort-asc")?($(".sort").removeClass("sort-asc").removeClass("sort-desc"),$(this).addClass("sort-desc")):($(".sort").removeClass("sort-asc").removeClass("sort-desc"),$(this).addClass("sort-asc"));var a=$(this).parents("table"),n=$(this).parents(".ico_transactions_block").attr("id"),i=$("#ico_part_filter").find('a[href="#'+n+'"]').parent().attr("id");e(0,i,a,"/admin/wallet/search-ico-transactions","total"===n)}),$("#marketing_part_filter li").each(function(){var t=$(this).attr("id"),a=$(this).find("a").attr("href"),n=$(a).find("table");e(0,t,n,"/admin/wallet/search-marketing-transactions","#marketing-total"===a)}),$(".marketing_transactions_block .pagination").on("click",".page-link",function(t){t.preventDefault();var a=$(this).parents(".pagination"),n=a.find(".page-item.active"),i=$(this).parents(".page-item");if($(this).hasClass("prev-page-link")&&(i=(n=a.find(".page-item.active")).prev()).find(".prev-page-link").length)return!1;if($(this).hasClass("next-page-link")&&(i=(n=a.find(".page-item.active")).next()).find(".next-page-link").length)return!1;n.removeClass("active"),i.addClass("active");var s=$(this).parents(".marketing_transactions_block").find("table"),r=$(this).parents(".marketing_transactions_block").attr("id"),o=$("#marketing_part_filter").find('a[href="#'+r+'"]').parent().attr("id");e(0,o,s,"/admin/wallet/search-marketing-transactions","company-total"===r)}),$(".marketing_transactions_block  .sort").on("click",function(t){t.preventDefault(),$(this).hasClass("sort-asc")?($(".sort").removeClass("sort-asc").removeClass("sort-desc"),$(this).addClass("sort-desc")):($(".sort").removeClass("sort-asc").removeClass("sort-desc"),$(this).addClass("sort-asc"));var a=$(this).parents("table"),n=$(this).parents(".marketing_transactions_block").attr("id"),i=$("#marketing_part_filter").find('a[href="#'+n+'"]').parent().attr("id");e(0,i,a,"/admin/wallet/search-marketing-transactions","company-total"===n)}),$("#company_part_filter li").each(function(){var t=$(this).attr("id"),a=$(this).find("a").attr("href"),n=$(a).find("table");e(0,t,n,"/admin/wallet/search-company-transactions","#company-total"===a)}),$(".company_transactions_block .pagination").on("click",".page-link",function(t){t.preventDefault();var a=$(this).parents(".pagination"),n=a.find(".page-item.active"),i=$(this).parents(".page-item");if($(this).hasClass("prev-page-link")&&(i=(n=a.find(".page-item.active")).prev()).find(".prev-page-link").length)return!1;if($(this).hasClass("next-page-link")&&(i=(n=a.find(".page-item.active")).next()).find(".next-page-link").length)return!1;n.removeClass("active"),i.addClass("active");var s=$(this).parents(".company_transactions_block").find("table"),r=$(this).parents(".company_transactions_block").attr("id"),o=$("#company_part_filter").find('a[href="#'+r+'"]').parent().attr("id");e(0,o,s,"/admin/wallet/search-company-transactions","company-total"===r)}),$(".company_transactions_block  .sort").on("click",function(t){t.preventDefault(),$(this).hasClass("sort-asc")?($(".sort").removeClass("sort-asc").removeClass("sort-desc"),$(this).addClass("sort-desc")):($(".sort").removeClass("sort-asc").removeClass("sort-desc"),$(this).addClass("sort-asc"));var a=$(this).parents("table"),n=$(this).parents(".company_transactions_block").attr("id"),i=$("#company_part_filter").find('a[href="#'+n+'"]').parent().attr("id");e(0,i,a,"/admin/wallet/search-company-transactions","company-total"===n)})})}});