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
(window.wpackiobonesThemeappJsonp=window.wpackiobonesThemeappJsonp||[]).push([[0],{25:function(e,t,o){o(26),e.exports=o(53)},53:function(e,t,o){"use strict";o.r(t);var c=o(55),a=o(22),l=o.n(a),n=o(23),i=o.n(n),s=function(e){l()({text:e,duration:3e3,gravity:"bottom",position:"center",escapeMarkup:!1,style:{background:"#fff",color:"#000",boxShadow:"0px 2px 4px rgba(0, 0, 0, 0.5)"}}).showToast()},d=function(){document.body.classList.remove("collection_action","collection_action_new","collection_action_image","collection_action_edit","collection_action_delete","collection_action_loading","collection_action_edit_image","collection_action_complete","account_saved","collection_action_notes","collection_action_manage_image","collection_action_remove_image","collection_help")};document.addEventListener("DOMContentLoaded",(function(){var e,t,o;document.querySelector(".display-social-links")&&document.querySelectorAll(".display-social-links .heart, .display-social-links .display-brochure").forEach((function(e){e.addEventListener("click",(function(e){var t=e.target.classList.contains("unsave"),o={action:t?"homebase_unsave":"homebase_save",post_ID:e.target.dataset.postId,post_type:e.target.dataset.postType},a={post:"Blog Post",display:"Display"},l="";l=e.target.classList.contains("heart")?t?"<h4>"+a[o.post_type]+" Removed</h4><p>This "+a[o.post_type]+" has been removed from My Account</p>":"<h4>"+a[o.post_type]+" Saved</h4><p>This "+a[o.post_type]+" has been saved to My Account</p>":t?"<p>This brochure has been removed from My Account<p>":"<h4>Brochure Saved</h4><p>This brochure is now available to view in My Account</p>",c.a.get(home_base.ajax_url,{params:o}).then((function(t){e.target.classList.toggle("unsave"),s(l)})).catch((function(e){console.log(e),s("Something has gone wrong")}))}))})),document.querySelectorAll(".display-image-gallery .heart").forEach((function(e){e.addEventListener("click",(function(e){var t=e.target.dataset.postId,o=e.target.dataset.imageThumbUrl;document.getElementById("save_image_id").value=t,document.querySelector("#new_image_form .collection_image_preview").style.backgroundImage="url("+o+")",document.body.classList.add("collection_action","collection_action_image")}))})),null===(e=document.querySelector("#collections, #displays"))||void 0===e||e.addEventListener("click",(function(e){if(e.preventDefault(),"add_new_collection"!==e.target.id&&"add_first_collection"!==e.target.id||document.body.classList.add("collection_action","collection_action_new"),e.target.classList.contains("collection_link")){var t,o=e.target.href,a=e.target.attributes.href.value;history.pushState({},"",o),document.querySelector("#collection_names").classList.add("hidden"),document.querySelectorAll(".collection").forEach((function(e){return e.classList.remove("active")})),null===(t=document.querySelector(a))||void 0===t||t.classList.add("active")}if(e.target.classList.contains("close_collection")&&(document.querySelector("#collection_names").classList.remove("hidden"),document.querySelectorAll(".collection").forEach((function(e){return e.classList.remove("active")})),history.pushState({},"","#collections")),e.target.classList.contains("edit_collection")&&(document.querySelector("#edit_collection_name").value=e.target.dataset.collectionName,document.querySelector("#edit_collection_id").value=e.target.dataset.collection,document.body.classList.add("collection_action","collection_action_edit")),e.target.classList.contains("delete_collection")&&(document.querySelector("#delete_collection_id").value=e.target.dataset.collection,document.body.classList.add("collection_action","collection_action_delete")),e.target.classList.contains("edit_notes")){var l={action:"homebase_fetch_notes",post_ID:e.target.dataset.postId};document.body.classList.add("collection_action_loading"),c.a.get(home_base.ajax_url,{params:l}).then((function(e){console.log(e.data.data),document.querySelector("#saved_notes").value=e.data.data.notes,document.querySelector("#saved_notes_id").value=e.data.data.post_id,document.body.classList.remove("collection_action_loading"),document.body.classList.add("collection_action","collection_action_notes")})).catch((function(e){console.log(e),s("Something has gone wrong")}))}if(e.target.classList.contains("collection-image-link")){console.log("collection-image-link");var n=e.target.dataset.gallery;document.body.classList.add("showing_gallery"),document.querySelector("#gallery_"+n).classList.add("active"),null.resize()}if(e.target.classList.contains("close_full_gallery")){document.body.classList.remove("showing_gallery"),e.target.closest(".full_gallery").classList.remove("active");var i=e.target.closest(".full_gallery").dataset.collection;history.pushState({},"","#collection_"+i)}if(e.target.classList.contains("edit_image")){var d=e.target.dataset.image,r=e.target.dataset.imageThumbUrl,u=e.target.dataset.collection;document.querySelector("#edit_image_id").value=d,document.querySelector("#original_collection_id").value=u,document.querySelector("#edit_image_form .collection_image_preview").style.backgroundImage="url("+r+")",document.body.classList.add("collection_action","collection_action_edit_image"),_(u)}})),null===(t=document.querySelector(".account_modal_close"))||void 0===t||t.addEventListener("click",(function(e){d()})),document.querySelectorAll("input[name='existing_collection_id']").forEach((function(e){e.addEventListener("change",(function(e){document.querySelector("#new_image_collection_name").value="",document.querySelector("#new_image_collection_name").classList.toggle("active",0===parseInt(e.target.value))}))})),null===(o=document.querySelector("#new_image_form"))||void 0===o||o.addEventListener("submit",(function(e){if(e.preventDefault(),document.querySelector("#save_image_id")){var t=document.querySelector("#save_image_id").value,o={action:"homebase_save_image",post_ID:t,existing_collection_id:document.querySelector("input[name=existing_collection_id]:checked").value,new_collection_name:document.querySelector("#new_image_collection_name").value};if(!o.existing_collection_id||0===o.existing_collection_id&&0===o.new_collection_name.length)return;document.body.classList.add("collection_action_loading"),c.a.get(home_base.ajax_url,{params:o}).then((function(e){var o,c,a,l;document.body.classList.remove("collection_action_loading"),console.log(document.querySelector("#photo_"+t+" .myaccount")),console.log("#photo_"+t+" .myaccount"),null===(o=document.querySelector("#photo_"+t+" .myaccount"))||void 0===o||o.classList.remove("saveimage"),null===(c=document.querySelector("#photo_"+t+" .myaccount"))||void 0===c||c.classList.add("unsaveimage"),null===(a=document.querySelector("#view_photo_"+t+" .myaccount"))||void 0===a||a.classList.remove("saveimage"),null===(l=document.querySelector("#view_photo_"+t+" .myaccount"))||void 0===l||l.classList.add("unsaveimage"),d(),s("<h5>Image Saved</h5><p>This image has been saved to My Account</p><p><a class='saved_view_account' href='/my-account'>View My Account</a></p>")})).catch((function(e){console.log(e),s("Something has gone wrong")}))}})),document.querySelector("#edit_notes_email_button").addEventListener("click",(function(e){e.preventDefault();var t=document.querySelector("#saved_notes").value;window.location="mailto:?subject=Note from Home Base My Account&body="+t})),document.querySelectorAll("#new_collection_form, #delete_collection_form, #edit_collection_form, #edit_notes_form, #edit_image_form, #delete_image_submit").forEach((function(e){var t="FORM"===e.tagName?"submit":"click";e.addEventListener(t,(function(e){e.preventDefault(),r(e.target.id)}))})),document.querySelector("#edit_image_collections").addEventListener("change",(function(e){"destination_collection_id"===e.target.name&&(document.querySelector("#new_destination_collection_name").value="",document.querySelector("#new_destination_collection_name").classList.toggle("active",0===parseInt(e.target.value)))})),document.querySelector("#collections")&&u()}));var r=function(e){var t={},o="";switch(e){case"new_collection_form":t={action:"homebase_add_collection",new_collection_name:document.querySelector("#new_collection_name").value},o="<h4>New Collection Saved</h4><p>This Collection has been saved to My Account</p>";break;case"delete_collection_form":t={action:"homebase_delete_collection",collection:document.querySelector("#delete_collection_id").value},o="<h4>Collection Deleted</h4><p>This Collection has been removed from your account</p>";break;case"edit_collection_form":t={action:"homebase_edit_collection",collection:document.querySelector("#edit_collection_id").value,edit_collection_name:document.querySelector("#edit_collection_name").value},o="<h4>Collection Updated</h4><p>This Collection name has been updated</p>";break;case"edit_notes_form":t={action:"homebase_save_notes",post_ID:document.querySelector("#saved_notes_id").value,notes:document.querySelector("#saved_notes").value},o="<h4>Notes Saved</h4>";break;case"edit_image_form":t={action:"homebase_edit_image",image:document.querySelector("#edit_image_id").value,edit_action:document.querySelector("input[name=edit_image_action]:checked").value,original_collection_id:document.querySelector("#original_collection_id").value,destination_collection_id:document.querySelector("input[name=destination_collection_id]:checked").value,new_destination_collection_name:document.querySelector("#new_destination_collection_name").value},o="<h4>Image Updated</h4><p>Image has been moved/copied</p>";break;case"delete_image_submit":t={action:"homebase_delete_image",image:document.querySelector("#edit_image_id").value,original_collection_id:document.querySelector("#original_collection_id").value},o="<h4>Image Removed</h4><p>Image successfully removed from this collection</p>";break;default:return}new_collection_name&&(document.body.classList.add("collection_action_loading"),c.a.get(home_base.ajax_url,{params:t}).then((function(e){d(),u(),s(o)})).catch((function(e){console.log(e),s("Something has gone wrong")})))},u=function(e){document.querySelector("#collections").classList.add("loading"),c.a.get(home_base.ajax_url,{params:{action:"homebase_load_collections",collection:e}}).then((function(t){document.querySelector("#collections").classList.remove("loading"),document.querySelector("#collections > .wp-block-group").innerHTML=t.data,e=new i.a(".full_gallery_slides",{cellAlign:"left",contain:!0,pageDots:!1,hash:!0,selectedAttraction:.01,friction:.15,wrapAround:!0,arrowShape:{x0:10,x1:60,y1:50,x2:63,y2:47,x3:17}})})).catch((function(e){s("Something has gone wrong")}))},_=function(e){document.querySelector("#collections").classList.add("loading"),c.a.get(home_base.ajax_url,{params:{action:"homebase_load_destination_collections",exclude:e}}).then((function(e){document.querySelector("#collections").classList.remove("loading"),document.querySelector("#edit_image_collections").innerHTML=e.data})).catch((function(e){s("Something has gone wrong")}))},m=(o(51),o(52),o(24));document.addEventListener("DOMContentLoaded",(function(){document.querySelectorAll(".wp-block-home-base-post-grid-list button").forEach((function(e){e.addEventListener("click",(function(e){var t=e.target.closest("button");if(!t.classList.contains("is-active")){var o,c,a=document.querySelector(".wp-block-query .wp-block-post-template, .wp-block-display-categories-grid .wp-block-post-template");null===(o=t.closest(".wp-block-home-base-post-grid-list"))||void 0===o||null===(c=o.querySelector(".is-active"))||void 0===c||c.classList.remove("is-active"),t.classList.add("is-active"),a&&(t.classList.contains("grid")?(a.classList.remove("is-layout-flow"),a.classList.remove("wp-block-post-template-is-layout-flow"),a.classList.add("is-layout-grid"),a.classList.add("wp-block-post-template-is-layout-grid")):(a.classList.add("is-layout-flow"),a.classList.add("wp-block-post-template-is-layout-flow"),a.classList.remove("is-layout-grid"),a.classList.remove("wp-block-post-template-is-layout-grid")))}}))})),document.querySelectorAll(".wp-block-display-categories-slider .wp-block-post-template, .wp-block-query.is-style-slider .wp-block-post-template").forEach((function(e){Object(m.tns)({container:e,items:1,slideBy:"page",autoplay:!0,nav:!0,gutter:32,controls:!1,autoplayButtonOutput:!1,navPosition:"bottom",autoPlay:!1,speed:600,loop:!0})})),document.querySelectorAll('img[loading="lazy"]').forEach((function(e){!0===e.complete&&e.classList.add("has-loaded"),e.addEventListener("load",(function(e){e.target.classList.add("has-loaded")}))}))}))}},[[25,1,2]]]);
//# sourceMappingURL=main-d0f109b6.js.map