/*!
 * 
 * bonesTheme
 * 
 * @author 
 * @version 0.1.0
 * @link UNLICENSED
 * @license UNLICENSED
 * 
 * Copyright (c) 2025 
 * 
 * This software is released under the UNLICENSED License
 * https://opensource.org/licenses/UNLICENSED
 * 
 * Compiled with the help of https://wpack.io
 * A zero setup Webpack Bundler Script for WordPress
 */
(window.wpackiobonesThemeappJsonp=window.wpackiobonesThemeappJsonp||[]).push([[0],{25:function(e,t,o){o(26),e.exports=o(53)},53:function(e,t,o){"use strict";o.r(t);var c=o(55),a=o(12),l=o.n(a),n=o(20),i=o.n(n),s=function(e){l()({text:e,duration:6e3,gravity:"bottom",position:"center",escapeMarkup:!1,style:{background:"#fff",color:"#000",boxShadow:"0px 2px 4px rgba(0, 0, 0, 0.5)"}}).showToast()},r=function(){document.body.classList.remove("collection_action","collection_action_new","collection_action_image","collection_action_edit","collection_action_delete","collection_action_loading","collection_action_edit_image","collection_action_complete","account_saved","collection_action_notes","collection_action_manage_image","collection_action_remove_image","collection_help")};document.addEventListener("DOMContentLoaded",(function(){var e,t,o,a,l;document.querySelector(".display-save")&&document.querySelectorAll(".display-save .heart, .display-save .display-brochure").forEach((function(e){e.classList.contains("logged-out")||e.addEventListener("click",(function(e){var t=e.target.classList.contains("unsave"),o={action:t?"homebase_unsave":"homebase_save",post_ID:e.target.dataset.postId,post_type:e.target.dataset.postType},a={post:"Blog Post",display:"Display"},l="";l=e.target.classList.contains("heart")?t?a[o.post_type]+' has been removed from <a href="/my-account">My Account</a>':a[o.post_type]+' has been saved to <a href="/my-account">My Account</a>':t?'This brochure has been removed from your <a href="/my-account">My Account</a>':'This brochure is now available to view in <a href="/my-account">My Account</a>',c.a.get(home_base.ajax_url,{params:o}).then((function(t){e.target.classList.toggle("unsave"),s(l)})).catch((function(e){console.log(e),s("Something has gone wrong")}))}))})),document.querySelectorAll(".display-image-gallery .heart").forEach((function(e){e.addEventListener("click",(function(e){var t=e.target.dataset.postId,o=e.target.dataset.imageThumbUrl;e.target.classList.contains("unsaveimage")||(document.getElementById("save_image_id").value=t,document.querySelector("#new_image_form .collection_image_preview").style.backgroundImage="url("+o+")",document.body.classList.add("collection_action","collection_action_image"))}))})),document.querySelector(".display_photo")&&(document.querySelectorAll(".display_photo").forEach((function(e){e.addEventListener("click",(function(e){e.preventDefault(),e.target.classList.contains("heart")||(document.body.classList.add("showing_gallery"),document.querySelector(".full_gallery").classList.add("active"),document.querySelector(".full_gallery_slides").classList.add("active"),new i.a(".full_gallery_slides",{wrapAround:!0}))}))})),document.querySelectorAll(".close_full_gallery").forEach((function(e){e.addEventListener("click",(function(e){e.preventDefault(),document.body.classList.remove("showing_gallery"),document.querySelector(".full_gallery").classList.remove("active"),document.querySelector(".full_gallery_slides").classList.remove("active")}))}))),null===(e=document.querySelector("#collections, #displays"))||void 0===e||e.addEventListener("click",(function(e){e.preventDefault();var t,o=e.target;if("IMG"===o.nodeName&&(o=o.closest("a")),!o.hasAttribute("id")||"add_new_collection"!==o.id&&"add_first_collection"!==o.id||document.body.classList.add("collection_action","collection_action_new"),o.classList.contains("collection_link")){var a,l=o.href,n=o.attributes.href.value;history.pushState({},"",l),document.querySelector("#collection_names").classList.add("hidden"),document.querySelectorAll(".collection").forEach((function(e){return e.classList.remove("active")})),null===(a=document.querySelector(n))||void 0===a||a.classList.add("active")}if(o.classList.contains("close_collection")&&(document.querySelector("#collection_names").classList.remove("hidden"),document.querySelectorAll(".collection").forEach((function(e){return e.classList.remove("active")})),history.pushState({},"","#collections")),o.classList.contains("edit_collection")&&(document.querySelector("#edit_collection_name").value=o.dataset.collectionName,document.querySelector("#edit_collection_id").value=o.dataset.collection,document.body.classList.add("collection_action","collection_action_edit")),o.classList.contains("delete_collection")&&(document.querySelector("#delete_collection_id").value=o.dataset.collection,document.body.classList.add("collection_action","collection_action_delete")),o.classList.contains("edit_notes")){var r={action:"homebase_fetch_notes",post_ID:o.dataset.postId};document.body.classList.add("collection_action_loading"),c.a.get(home_base.ajax_url,{params:r}).then((function(e){console.log(e.data.data),document.querySelector("#saved_notes").value=e.data.data.notes,document.querySelector("#saved_notes_id").value=e.data.data.post_id,document.body.classList.remove("collection_action_loading"),document.body.classList.add("collection_action","collection_action_notes")})).catch((function(e){console.log(e),s("Something has gone wrong")}))}if(o.classList.contains("collection-image-link")){var d=o.dataset.gallery;document.body.classList.add("showing_gallery"),t=new i.a("#gallery_"+d+" .full_gallery_slides",{pageDots:!1,wrapAround:!0}),document.querySelector("#gallery_"+d).classList.add("active"),t.resize()}if(o.classList.contains("close_full_gallery")){document.body.classList.remove("showing_gallery"),o.closest(".full_gallery").classList.remove("active");var u=o.closest(".full_gallery").dataset.collection;history.pushState({},"","#collection_"+u),t&&t.destroy()}if(o.classList.contains("edit_image")){var _=o.dataset.image,v=o.dataset.imageThumbUrl,y=o.dataset.collection;document.querySelector("#edit_image_id").value=_,document.querySelector("#original_collection_id").value=y,document.querySelector("#edit_image_form .collection_image_preview").style.backgroundImage="url("+v+")",document.body.classList.add("collection_action","collection_action_edit_image"),m(y)}})),null===(t=document.querySelector(".close-wrapper, .account_modal_close"))||void 0===t||t.addEventListener("click",(function(e){r()})),document.querySelectorAll("input[name='existing_collection_id']").forEach((function(e){e.addEventListener("change",(function(e){document.querySelector("#new_image_collection_name").value="",document.querySelector("#new_image_collection_name").classList.toggle("active",0===parseInt(e.target.value))}))})),null===(o=document.querySelector("#new_image_form"))||void 0===o||o.addEventListener("submit",(function(e){if(e.preventDefault(),document.querySelector("#save_image_id")){var t=document.querySelector("#save_image_id").value,o={action:"homebase_save_image",post_ID:t,existing_collection_id:document.querySelector("input[name=existing_collection_id]:checked").value,new_collection_name:document.querySelector("#new_image_collection_name").value};if(!o.existing_collection_id||0===o.existing_collection_id&&0===o.new_collection_name.length)return;document.body.classList.add("collection_action_loading"),c.a.get(home_base.ajax_url,{params:o}).then((function(e){var o,c,a,l;document.body.classList.remove("collection_action_loading"),null===(o=document.querySelector("#photo_"+t+" .myaccount"))||void 0===o||o.classList.remove("saveimage"),null===(c=document.querySelector("#photo_"+t+" .myaccount"))||void 0===c||c.classList.add("unsaveimage"),null===(a=document.querySelector("#view_photo_"+t+" .myaccount"))||void 0===a||a.classList.remove("saveimage"),null===(l=document.querySelector("#view_photo_"+t+" .myaccount"))||void 0===l||l.classList.add("unsaveimage"),r()})).catch((function(e){console.log(e),s("Something has gone wrong")}))}})),null===(a=document.querySelector("#edit_notes_email_button"))||void 0===a||a.addEventListener("click",(function(e){e.preventDefault();var t=document.querySelector("#saved_notes").value;window.location="mailto:?subject=Note from Home Base My Account&body="+t})),document.querySelectorAll("#new_collection_form, #delete_collection_form, #edit_collection_form, #edit_notes_form, #edit_image_form, #delete_image_submit").forEach((function(e){var t="FORM"===e.tagName?"submit":"click";e.addEventListener(t,(function(e){e.preventDefault(),d(e.target.id)}))})),null===(l=document.querySelector("#edit_image_collections"))||void 0===l||l.addEventListener("change",(function(e){"destination_collection_id"===e.target.name&&(document.querySelector("#new_destination_collection_name").value="",document.querySelector("#new_destination_collection_name").classList.toggle("active",0===parseInt(e.target.value)))})),document.querySelector("#collections")&&u(),document.querySelectorAll(".show-help-modal").forEach((function(e){e.addEventListener("click",(function(e){e.preventDefault(),document.body.classList.add("collection_help","collection_action")}))}))}));var d=function(e){var t={};switch(e){case"new_collection_form":t={action:"homebase_add_collection",new_collection_name:document.querySelector("#new_collection_name").value},"<h4>New Collection Saved</h4><p>This Collection has been saved to My Account</p>";break;case"delete_collection_form":t={action:"homebase_delete_collection",collection:document.querySelector("#delete_collection_id").value},"<h4>Collection Deleted</h4><p>This Collection has been removed from your account</p>";break;case"edit_collection_form":t={action:"homebase_edit_collection",collection:document.querySelector("#edit_collection_id").value,edit_collection_name:document.querySelector("#edit_collection_name").value},"<h4>Collection Updated</h4><p>This Collection name has been updated</p>";break;case"edit_notes_form":t={action:"homebase_save_notes",post_ID:document.querySelector("#saved_notes_id").value,notes:document.querySelector("#saved_notes").value},"<h4>Notes Saved</h4>";break;case"edit_image_form":t={action:"homebase_edit_image",image:document.querySelector("#edit_image_id").value,edit_action:document.querySelector("select.edit_image_actions").value,original_collection_id:document.querySelector("#original_collection_id").value,destination_collection_id:document.querySelector("input[name=destination_collection_id]:checked").value,new_destination_collection_name:document.querySelector("#new_destination_collection_name").value},"<h4>Image Updated</h4><p>Image has been moved/copied</p>";break;case"delete_image_submit":t={action:"homebase_delete_image",image:document.querySelector("#edit_image_id").value,original_collection_id:document.querySelector("#original_collection_id").value},"<h4>Image Removed</h4><p>Image successfully removed from this collection</p>";break;default:return}new_collection_name&&(document.body.classList.add("collection_action_loading"),c.a.get(home_base.ajax_url,{params:t}).then((function(e){r(),u()})).catch((function(e){console.log(e),s("Something has gone wrong")})))},u=function(e){document.querySelector("#collections").classList.add("loading"),c.a.get(home_base.ajax_url,{params:{action:"homebase_load_collections",collection:e}}).then((function(e){document.querySelector("#collections").classList.remove("loading"),document.querySelector("#collections > .wp-block-group").innerHTML=e.data})).catch((function(e){s("Something has gone wrong")}))},m=function(e){document.querySelector("#collections").classList.add("loading"),c.a.get(home_base.ajax_url,{params:{action:"homebase_load_destination_collections",exclude:e}}).then((function(e){document.querySelector("#collections").classList.remove("loading"),document.querySelector("#edit_image_collections").innerHTML=e.data})).catch((function(e){s("Something has gone wrong")}))},_=(o(51),o(52),o(21)),v=function(){var e=document.querySelectorAll('.wp-block-home-base-search-block input[type="text"]');if(e.length>1)for(var t=e.item(0).value,o=1;o<e.length;o++)e[o].value=t;document.body.classList.remove("show-home-base-search")};document.addEventListener("DOMContentLoaded",(function(){var e,t;if(document.querySelector("header.wp-block-template-part")){var o=window.scrollY,c=!1;window.addEventListener("scroll",(function(e){c||(c=!0,setTimeout((function(){var e=o<window.scrollY&&window.scrollY>150;document.body.classList.toggle("header-hide",e),o=window.scrollY,c=!1}),100))}))}document.body.addEventListener("keydown",(function(e){"Escape"===e.key&&document.body.classList.contains("show-home-base-search")&&v()})),null===(e=document.querySelector(".wp-block-home-base-search-modal"))||void 0===e||e.addEventListener("click",(function(e){e.target.classList.contains("wp-block-home-base-search-modal")&&v()})),document.querySelectorAll(".wp-block-home-base-post-grid-list button").forEach((function(e){e.addEventListener("click",(function(e){var t=e.target.closest("button");if(!t.classList.contains("is-active")){var o,c,a=document.querySelector(".wp-block-query .wp-block-post-template, .wp-block-display-categories-grid .wp-block-post-template");null===(o=t.closest(".wp-block-home-base-post-grid-list"))||void 0===o||null===(c=o.querySelector(".is-active"))||void 0===c||c.classList.remove("is-active"),t.classList.add("is-active"),a&&(t.classList.contains("grid")?(a.classList.remove("is-layout-flow"),a.classList.remove("wp-block-post-template-is-layout-flow"),a.classList.add("is-layout-grid"),a.classList.add("wp-block-post-template-is-layout-grid")):(a.classList.add("is-layout-flow"),a.classList.add("wp-block-post-template-is-layout-flow"),a.classList.remove("is-layout-grid"),a.classList.remove("wp-block-post-template-is-layout-grid")))}}))})),document.querySelectorAll(".wp-block-display-categories-slider .wp-block-post-template, .wp-block-query.is-style-slider .wp-block-post-template").forEach((function(e){Object(_.tns)({container:e,items:1,slideBy:"page",autoplay:!0,nav:!0,gutter:10,controls:!1,autoplayButtonOutput:!1,navPosition:"bottom",autoPlay:!1,speed:600,loop:!0,responsive:{782:{gutter:32}}})})),document.querySelectorAll(".review-slider").forEach((function(e){Object(_.tns)({container:e,items:1,slideBy:"page",autoplay:!0,nav:!1,gutter:16,controls:!0,autoplayButtonOutput:!1,navPosition:"bottom",autoPlay:!1,speed:600,loop:!0,responsive:{782:{items:3,gutter:54}}})})),document.querySelectorAll('img[loading="lazy"]').forEach((function(e){!0===e.complete&&e.classList.add("has-loaded"),e.addEventListener("load",(function(e){e.target.classList.add("has-loaded")}))})),null===(t=document.querySelector(".wp-block-button.header-search-trigger"))||void 0===t||t.addEventListener("click",(function(e){e.preventDefault(),document.body.classList.toggle("show-home-base-search")})),document.querySelectorAll('.wp-site-blocks a[href*="#"]').forEach((function(e){e.addEventListener("click",(function(e){var t,o=null===(t=e.target.closest("a").href)||void 0===t?void 0:t.split("#");2===o.length&&document.querySelector("#".concat(o[1]))&&(e.preventDefault(),document.querySelector("#".concat(o[1])).scrollIntoView({behavior:"smooth"}))}))})),document.querySelectorAll(".wp-block-my-profile .config > a").forEach((function(e){e.addEventListener("click",(function(e){e.preventDefault(),console.log(e.target.closest("a").href);var t,o=e.target.closest(".config");o&&(null===(t=o.querySelector(".dropdown"))||void 0===t||t.classList.toggle("visible"))}))}));document.body.addEventListener("wc-blocks_added_to_cart",(function(e){document.body.classList.contains("cart-total-0")&&document.body.classList.remove("cart-total-0")}))})),document.addEventListener("DOMContentLoaded",(function(){document.addEventListener("change",(function(e){e.target.matches(".mc-variation-radios input")&&document.querySelectorAll(".mc-variation-radios input:checked").forEach((function(e){var t=e.name,o=e.value,c=document.querySelector('select[name="'+t+'"]');if(c){c.value=o;var a=new Event("change",{bubbles:!0});c.dispatchEvent(a)}}))}));var e=document.querySelectorAll('.woocommerce div.product.product-type-grouped input[type="checkbox"]');if(e.forEach((function(t,o){t.addEventListener("click",(function(t){if(o<e.length-1){for(var c=!1,a=0;a<e.length-1;a++)c=c||e[a].checked;e[e.length-1].disabled=c,e[e.length-1].checked=!1}else{var l=t.target.checked;e.forEach((function(e){e!==t.target&&(e.disabled=l,e.checked=!1)}))}for(var n,i,s=0,r=0;r<e.length;r++)if(e[r].checked){var d=e[r].closest("tr").querySelector(".amount");d&&(s+=parseFloat((n=d.innerHTML,i=void 0,(i=n.match(/\d+\.\d+/))?i[0]:null)))}var u=document.querySelector(".product-description .price");u&&(void 0===u.dataset.og&&(u.dataset.og=document.querySelector(".product-description .price").innerHTML),u.innerHTML=0===s?u.dataset.og:"$"+s)}))})),document.querySelectorAll("form.cart").forEach((function(e){var t=null;if(e.classList.contains("variations_form")&&(t=JSON.parse(e.dataset.product_variations))){var o=t.filter((function(e){return e.is_in_stock})).map((function(e){return e.attributes}));e.querySelectorAll(".mc-variation-radios li").forEach((function(e){var t,c,a=null===(t=e.querySelector("input[type='radio']"))||void 0===t?void 0:t.value,l=null===(c=e.querySelector("input[type='radio']"))||void 0===c?void 0:c.name;a&&l&&(0===o.filter((function(e){return e[l]===a})).length&&e.remove())}))}})),document.querySelectorAll(".requires-login").forEach((function(e){e.addEventListener("click",(function(e){var t;e.preventDefault(),t='This feature requires you log in to <a href="/my-account">My Account</a>',l()({text:t,duration:6e3,gravity:"bottom",position:"center",escapeMarkup:!1,style:{background:"#fff",color:"#000",boxShadow:"0px 2px 4px rgba(0, 0, 0, 0.5)",link:"#ff00ff"}}).showToast()}))})),document.querySelectorAll(".display-lead-phone_number").forEach((function(e){var t=e.dataset.id;e.addEventListener("click",(function(e){e.preventDefault(),document.querySelector(".display-lead-phone-modal[data-id='"+t+"']")&&document.querySelector(".display-lead-phone-modal[data-id='"+t+"']").showModal()}))})),document.querySelectorAll(".display-lead-email").forEach((function(e){e.dataset.id;e.addEventListener("click",(function(e){e.preventDefault();var t=e.target.closest("a"),o=t.dataset.displayName,c=t.dataset.displayEmail;document.querySelector(".display-email-modal")&&(document.querySelector(".display-email-modal .post-title").innerHTML=o,document.querySelector(".display-email-modal #field_supplier__field").value=c,document.querySelector(".display-email-modal").showModal())}))})),document.querySelectorAll(".close-dialog").forEach((function(e){e.addEventListener("click",(function(e){e.target.closest("dialog").close()}))})),document.querySelector("#customer-order-details")){var t=document.querySelector("#customer-order-details").dataset;t.attendees&&"hide"===t.attendees&&document.querySelectorAll(".attendee2, .attendee_3").forEach((function(e){return e.style.display="none"})),t.attendeeCount&&"2"===t.attendeeCount&&t.attendees&&"show"===t.attendees&&document.querySelectorAll(".attendee_3").forEach((function(e){return e.style.display="none"}))}}))}},[[25,1,2]]]);
//# sourceMappingURL=main-109f06d9.js.map