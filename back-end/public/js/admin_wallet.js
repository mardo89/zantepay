!function(t){var n={};function a(e){if(n[e])return n[e].exports;var i=n[e]={i:e,l:!1,exports:{}};return t[e].call(i.exports,i,i.exports,a),i.l=!0,i.exports}a.m=t,a.c=n,a.d=function(t,n,e){a.o(t,n)||Object.defineProperty(t,n,{configurable:!1,enumerable:!0,get:e})},a.n=function(t){var n=t&&t.__esModule?function(){return t.default}:function(){return t};return a.d(n,"a",n),n},a.o=function(t,n){return Object.prototype.hasOwnProperty.call(t,n)},a.p="",a(a.s=62)}({1:function(t,n){var a=Object.assign||function(t){for(var n=1;n<arguments.length;n++){var a=arguments[n];for(var e in a)Object.prototype.hasOwnProperty.call(a,e)&&(t[e]=a[e])}return t};window.getSpinner=function(t){return $("<div />").addClass("spinner spinner--"+t).append($("<div />")).append($("<div />")).append($("<div />")).append($("<div />"))},window.showSpinner=function(t){t.addClass("is-loading").prop("disabled",!0),t.append(getSpinner(30))},window.hideSpinner=function(t){t.removeClass("is-loading").prop("disabled",!1),t.find(".spinner").remove()},window.clearErrors=function(){$(".form-error").removeClass("form-error"),$(".error-text").remove()},window.showError=function(t){$.magnificPopup.open({items:{src:"#error-modal"},type:"inline",closeOnBgClick:!0,callbacks:{elementParse:function(n){$(n.src).find("#error-message").text(t)}}})},window.validateFile=function(t){var n=t.type.match(/(.png)|(.jpeg)|(.jpg)|(.pdf)$/i),a=t.size.toFixed(0)<4194304;return!(!n||!a)},window.scrollToError=function(){$("html, body").animate({scrollTop:$(".form-error:eq(0)").offset().top},500)},window.showConfirmation=function(t,n,a){$.magnificPopup.open({items:{src:"#confirmation-modal"},type:"inline",showCloseBtn:!1,closeOnBgClick:!1,callbacks:{elementParse:function(a){$(a.src).find("#confirmation-message").text(t),$(a.src).find("#accept_action").off("click").on("click",function(t){t.preventDefault(),$.magnificPopup.close(),"function"==typeof n&&n()}),$(a.src).find("#reject_action").off("click").on("click",function(t){t.preventDefault(),$.magnificPopup.close()})}}})},window.showPopover=function(t){$(".popover").remove();var n=$("<div />").addClass("popover").append($("<i />").addClass("fa fa-check-circle")).append($("<div />").addClass("popover__content").html(t)).append($("<a />").addClass("popover__close").attr("href","").html("Close").on("click",function(t){t.preventDefault(),$(".popover").remove()}));$("body").prepend(n),setTimeout(function(){n.remove()},5e3)},window.showProtectionDialog=function(t){$.magnificPopup.open({items:{src:"#protection-modal"},type:"inline",showCloseBtn:!0,closeOnBgClick:!0,callbacks:{elementParse:function(n){$(n.src).find("#frm_protection").find('input[name="signature"]').val(""),$(n.src).find("#frm_protection").off("submit").on("submit",function(n){n.preventDefault(),sessionStorage.setItem("signature",$(this).find('input[name="signature"]').val()),$.magnificPopup.close(),t()})}}})},window.processProtectionRequest=function(t,n){var e=sessionStorage.getItem("signature");if(sessionStorage.removeItem("signature"),!e){var i=(new Date).valueOf();return sessionStorage.setItem("action_timestamp",i),a({},n,{action:t,action_timestamp:i})}return a({},n,{action_timestamp:sessionStorage.getItem("action_timestamp"),signature:e})},window.processProtectionResponse=function(t,n,a){205==t?"function"==typeof n&&showProtectionDialog(n):"function"==typeof a&&a()}},62:function(t,n,a){t.exports=a(63)},63:function(t,n,a){a(1);var e=function(t,n,a,e,i){var r="ICO_TOTAL"!==n?n:"",s=[];$('input[name="'+t+'_status_filter"]:checked').each(function(){s.push($(this).val())});var o=a.next("nav").find(".pagination"),c=parseInt(o.find(".page-item.active .page-link").html()),p=isNaN(c)?1:c,l=0,d="asc";a.find(".sort.sort-asc").length&&(l=$(".sort.sort-asc").index(),d="asc"),a.find(".sort.sort-desc").length&&(l=$(".sort.sort-desc").index(),d="desc"),a.find("tbody").empty(),a.find("tbody").append($("<tr />").addClass("operation-in-progress").append($("<td />").attr("colspan",4).append($("<a />").attr("href","").addClass("update-icon is-active")))),o.find(".page-item").empty(),axios.get(e,{params:{part_filter:r,status_filter:s,page:p,sort_index:l,sort_order:d}}).then(function(n){a.find(".operation-in-progress").remove(),n.data.transactionsList.forEach(function(n){var e="---";i&&(e=""===n.status?'<button class="btn btn--medium btn--shadowed-light grant_'+t+'_coins"  type="button">Issue Token</button>':n.status),a.find("tbody").append($("<tr />").data("uid",n.uid).append($("<td />").html(n.user)).append($("<td />").html(n.address)).append($("<td />").html(n.amount)).append($("<td />").html(e)))});for(var e=1;e<=n.data.paginator.totalPages;e++){var r=n.data.paginator.currentPage==e?"page-item active":"page-item";o.append($("<li />").addClass(r).append($("<a />").addClass("page-link").attr("href","#").html(e)))}n.data.paginator.totalPages>1&&o.prepend($("<li />").addClass("page-item").append($("<a />").addClass("page-link prev-page-link").attr("href","#").html("Previous"))).append($("<li />").addClass("page-item").append($("<a />").addClass("page-link next-page-link").attr("href","#").html("Next")))}).catch(function(t){a.find(".operation-in-progress").remove();var n=t.response.data.message;showError(n)})};$(document).ready(function(){$(document).on("click",".grant_ico_coins",function(t){t.preventDefault();var n=$(this);showSpinner(n),clearErrors();var a=$(this).parents("tr"),e={uid:a.data("uid"),address:a.find("td:eq(1)").text().trim(),amount:a.find("td:eq(2)").text().trim()};axios.post("/admin/wallet/grant-ico-coins",qs.stringify(e)).then(function(){hideSpinner(n),$.magnificPopup.open({items:{src:"#grant-coins-modal"},type:"inline",closeOnBgClick:!0})}).catch(function(t){hideSpinner(n);var a=t.response.data.message;showError(a)})}),$(document).on("click",".grant_marketing_coins",function(t){t.preventDefault();var n=$(this);showSpinner(n),clearErrors();var a=$(this).parents("tr"),e={uid:a.data("uid"),address:a.find("td:eq(1)").text().trim(),amount:a.find("td:eq(2)").text().trim()};axios.post("/admin/wallet/grant-marketing-coins",qs.stringify(e)).then(function(){hideSpinner(n),$.magnificPopup.open({items:{src:"#grant-coins-modal"},type:"inline",closeOnBgClick:!0})}).catch(function(t){hideSpinner(n);var a=t.response.data.message;showError(a)})}),$(document).on("click",".grant_company_coins",function(t){t.preventDefault();var n=$(this);showSpinner(n),clearErrors();var a=$(this).parents("tr"),e={uid:a.data("uid"),address:a.find("td:eq(1)").text().trim(),amount:a.find("td:eq(2)").text().trim()};axios.post("/admin/wallet/grant-company-coins",qs.stringify(e)).then(function(){hideSpinner(n),$.magnificPopup.open({items:{src:"#grant-coins-modal"},type:"inline",closeOnBgClick:!0})}).catch(function(t){hideSpinner(n);var a=t.response.data.message;showError(a)})}),$("#grant_marketing_coins").on("click",function(t){t.preventDefault();var n=$(this);showSpinner(n),clearErrors();var a={address:$("#grant_marketing_address").val(),amount:$("#grant_marketing_amount").val()};axios.post("/admin/wallet/grant-marketing-coins",qs.stringify(a)).then(function(){hideSpinner(n),$.magnificPopup.open({items:{src:"#grant-coins-modal"},type:"inline",closeOnBgClick:!0})}).catch(function(t){hideSpinner(n);var a=t.response.data.message;showError(a)})}),$("#grant_company_coins").on("click",function(t){t.preventDefault();var n=$(this);showSpinner(n),clearErrors();var a={address:$("#grant_company_address").val(),amount:$("#grant_company_amount").val()};axios.post("/admin/wallet/grant-company-coins",qs.stringify(a)).then(function(){hideSpinner(n),$.magnificPopup.open({items:{src:"#grant-coins-modal"},type:"inline",closeOnBgClick:!0})}).catch(function(t){hideSpinner(n);var a=t.response.data.message;showError(a)})}),$("#ico_part_filter li").each(function(){var t=$(this).attr("id"),n=$(this).find("a").attr("href"),a=$(n).find("table");e("ico",t,a,"/admin/wallet/search-ico-transactions","#total"===n)}),$(".ico_transactions_block .pagination").on("click",".page-link",function(t){t.preventDefault();var n=$(this).parents(".pagination"),a=n.find(".page-item.active"),i=$(this).parents(".page-item");if($(this).hasClass("prev-page-link")&&(i=(a=n.find(".page-item.active")).prev()).find(".prev-page-link").length)return!1;if($(this).hasClass("next-page-link")&&(i=(a=n.find(".page-item.active")).next()).find(".next-page-link").length)return!1;a.removeClass("active"),i.addClass("active");var r=$(this).parents(".ico_transactions_block").find("table"),s=$(this).parents(".ico_transactions_block").attr("id"),o=$("#ico_part_filter").find('a[href="#'+s+'"]').parent().attr("id");e("ico",o,r,"/admin/wallet/search-ico-transactions","total"===s)}),$(".ico_transactions_block  .sort").on("click",function(t){t.preventDefault(),$(this).hasClass("sort-asc")?($(".sort").removeClass("sort-asc").removeClass("sort-desc"),$(this).addClass("sort-desc")):($(".sort").removeClass("sort-asc").removeClass("sort-desc"),$(this).addClass("sort-asc"));var n=$(this).parents("table"),a=$(this).parents(".ico_transactions_block").attr("id"),i=$("#ico_part_filter").find('a[href="#'+a+'"]').parent().attr("id");e("ico",i,n,"/admin/wallet/search-ico-transactions","total"===a)}),$("#marketing_part_filter li").each(function(){var t=$(this).attr("id"),n=$(this).find("a").attr("href"),a=$(n).find("table");e("marketing",t,a,"/admin/wallet/search-marketing-transactions","#marketing-total"===n)}),$(".marketing_transactions_block .pagination").on("click",".page-link",function(t){t.preventDefault();var n=$(this).parents(".pagination"),a=n.find(".page-item.active"),i=$(this).parents(".page-item");if($(this).hasClass("prev-page-link")&&(i=(a=n.find(".page-item.active")).prev()).find(".prev-page-link").length)return!1;if($(this).hasClass("next-page-link")&&(i=(a=n.find(".page-item.active")).next()).find(".next-page-link").length)return!1;a.removeClass("active"),i.addClass("active");var r=$(this).parents(".marketing_transactions_block").find("table"),s=$(this).parents(".marketing_transactions_block").attr("id"),o=$("#marketing_part_filter").find('a[href="#'+s+'"]').parent().attr("id");e("marketing",o,r,"/admin/wallet/search-marketing-transactions","company-total"===s)}),$(".marketing_transactions_block  .sort").on("click",function(t){t.preventDefault(),$(this).hasClass("sort-asc")?($(".sort").removeClass("sort-asc").removeClass("sort-desc"),$(this).addClass("sort-desc")):($(".sort").removeClass("sort-asc").removeClass("sort-desc"),$(this).addClass("sort-asc"));var n=$(this).parents("table"),a=$(this).parents(".marketing_transactions_block").attr("id"),i=$("#marketing_part_filter").find('a[href="#'+a+'"]').parent().attr("id");e("marketing",i,n,"/admin/wallet/search-marketing-transactions","company-total"===a)}),$("#company_part_filter li").each(function(){var t=$(this).attr("id"),n=$(this).find("a").attr("href"),a=$(n).find("table");e("company",t,a,"/admin/wallet/search-company-transactions","#company-total"===n)}),$(".company_transactions_block .pagination").on("click",".page-link",function(t){t.preventDefault();var n=$(this).parents(".pagination"),a=n.find(".page-item.active"),i=$(this).parents(".page-item");if($(this).hasClass("prev-page-link")&&(i=(a=n.find(".page-item.active")).prev()).find(".prev-page-link").length)return!1;if($(this).hasClass("next-page-link")&&(i=(a=n.find(".page-item.active")).next()).find(".next-page-link").length)return!1;a.removeClass("active"),i.addClass("active");var r=$(this).parents(".company_transactions_block").find("table"),s=$(this).parents(".company_transactions_block").attr("id"),o=$("#company_part_filter").find('a[href="#'+s+'"]').parent().attr("id");e("foundation",o,r,"/admin/wallet/search-company-transactions","company-total"===s)}),$(".company_transactions_block  .sort").on("click",function(t){t.preventDefault(),$(this).hasClass("sort-asc")?($(".sort").removeClass("sort-asc").removeClass("sort-desc"),$(this).addClass("sort-desc")):($(".sort").removeClass("sort-asc").removeClass("sort-desc"),$(this).addClass("sort-asc"));var n=$(this).parents("table"),a=$(this).parents(".company_transactions_block").attr("id"),i=$("#company_part_filter").find('a[href="#'+a+'"]').parent().attr("id");e("company",i,n,"/admin/wallet/search-company-transactions","company-total"===a)})})}});