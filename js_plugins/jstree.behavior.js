/**
 * jstree.behavior.js
 * js code that adds jstree functionality to an unordered list in the markup.
 *
 * User: Spiros Kabasaskalis,kabasakalis@gmail.com,
 * http://.reverbnation/spiroskabasakalis
 * http://iws/kabasakalis.gr
 * Licensed under the MIT licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * Date: February 2nd,2013
 * Time: 1:57 AM
 *
 */

$(function() {

  var spinnneropts = {
    lines: 13, // The number of lines to draw
    length: 7, // The length of each line
    width: 4, // The line thickness
    radius: 20, // The radius of the inner circle
    corners: 1, // Corner roundness (0..1)
    rotate: 0, // The rotation offset
    color: '#000', // #rgb or #rrggbb
    speed: 1, // Rounds per second
    trail: 60, // Afterglow percentage
    shadow: false, // Whether to render a shadow
    hwaccel: false, // Whether to use hardware acceleration
    className: 'spinner', // The CSS class to assign to the spinner
    zIndex: 2e9, // The z-index (defaults to 2000000000)
    top: 'auto', // Top position relative to parent in px
    left: 'auto' // Left position relative to parent in px
  };
  var spinnertarget = document.getElementById(JsTreeBehavior.container_ID);
  var spinner = new Spinner(spinnneropts);
  $("#" + JsTreeBehavior.container_ID)
          .jstree({
// the `plugins` array allows you to configure the active plugins on this instance
    "plugins": JsTreeBehavior.plugins,
    // each plugin you have included can have its own config object
    // it makes sense to configure a plugin only if overriding the defaults
    "ui": {"select_limit": 1}, //note if you enable multi selection server side move/copy actions may exhibit buggy behavior.
    "core": {"initially_open": JsTreeBehavior.open_nodes, 'open_parents': true},
    "themes": JsTreeBehavior.themes,
    "html_data": {
      "ajax": {
        "type": "POST",
        "url": Yii_js.baseUrl + "/" + JsTreeBehavior.controllerID + "/fetchTree",
        "data": function(n) {
          return {
            id: n.attr ? n.attr("id") : 0,
            "YII_CSRF_TOKEN": Yii_js.csrf
          };
        }
      }
    },
    "contextmenu": {'items': {
        "rename": false,
        "remove": {
          "label": "Удалить",
          "action": function(obj) {
            var msg = (obj).attr('rel') + " (" + (obj).attr('id') + ") " + "со всеми подкатегориями будет удален! Вы уверены?"
            var n = noty({
              text: msg,
              type: 'warning',
              dismissQueue: true,
              modal: true,
              layout: 'center',
              theme: 'defaultTheme',
              buttons: [
                {addClass: 'btn btn-primary', text: 'Да, Удалить!', onClick: function($noty) {
                    jQuery("#" + JsTreeBehavior.container_ID).jstree("remove", obj);
                    $noty.close();
//                    noty({dismissQueue: true, force: true, layout: 'center', theme: 'defaultTheme', text: 'You just deleted ' + (obj).attr('rel'), type: 'success'});
                  }
                },
                {addClass: 'btn btn-danger', text: 'Отменить', onClick: function($noty) {
                    $noty.close();
                    // noty({dismissQueue: true, force: true, layout: layout, theme: 'defaultTheme', text: 'You clicked "Cancel" button', type: 'error'});
                  }
                }
              ]
            });
          }
        }, //remove
        "create": {
          "label": "Новый",
          "action": function(obj) {
            this.create(obj);
          },
          "separator_after": false
        }
      }//items
    } //context menu
  })  //jstree

          ///EVENTS
          .bind("select_node.jstree", function(event, data) {
// `data.rslt.obj` is the jquery extended node that was clicked
// alert(data.rslt.obj.attr("id"));
      var id = data.rslt.obj.attr("id").replace("node_", "");
      spinner.spin(spinnertarget);
      $('#category-form').load(Yii_js.baseUrl + "/" + JsTreeBehavior.controllerID + "/returnForm", {
        'update_id': id,
        "YII_CSRF_TOKEN": Yii_js.csrf
      }, function() {
        spinner.stop();
      });
  })

          .bind("remove.jstree", function(e, data) {
    $.ajax({
      type: "POST",
      url: Yii_js.baseUrl + "/" + JsTreeBehavior.controllerID + "/remove",
      data: {
        "id": data.rslt.obj.attr("id").replace("node_", ""),
        "YII_CSRF_TOKEN": Yii_js.csrf
      },
      beforeSend: function() {
        spinner.spin(spinnertarget);
      },
      complete: function() {
        spinner.stop();
      },
      success: function(r) {
        response = $.parseJSON(r);
        if (!response.success) {
          $.jstree.rollback(data.rlbk);
        }
        ;
      }
    });
  })

          .bind("create.jstree", function(e, data) {
    newname = data.rslt.name;
    parent_id = data.rslt.parent.attr("id").replace("node_", "");
    $.ajax({
      type: "POST",
      url: Yii_js.baseUrl + "/" + JsTreeBehavior.controllerID + "/returnForm",
      data: {'name': newname,
        'parent_id': parent_id,
        "YII_CSRF_TOKEN": Yii_js.csrf
      },
      beforeSend: function() {
        spinner.spin(spinnertarget);
      },
      complete: function() {
        spinner.stop();
      },
      success: function(data) {
        $('#category-form').html(data);
      } //success
    }); //ajax

  })
          .bind("move_node.jstree", function(e, data) {
    data.rslt.o.each(function(i) {

//jstree provides a whole  bunch of properties for the move_node event
//not all are needed for this view,but they are there if you need them.
//Commented out logs  are for debugging and exploration of jstree.

      next = jQuery.jstree._reference("#" + JsTreeBehavior.container_ID)._get_next(this, true);
      previous = jQuery.jstree._reference("#" + JsTreeBehavior.container_ID)._get_prev(this, true);
      pos = data.rslt.cp;
      moved_node = $(this).attr('id').replace("node_", "");
      next_node = next != false ? $(next).attr('id').replace("node_", "") : false;
      previous_node = previous != false ? $(previous).attr('id').replace("node_", "") : false;
      new_parent = $(data.rslt.np).attr('id').replace("node_", "");
      old_parent = $(data.rslt.op).attr('id').replace("node_", "");
      ref_node = $(data.rslt.r).attr('id').replace("node_", "");
      ot = data.rslt.ot;
      rt = data.rslt.rt;
      copy = typeof data.rslt.cy != 'undefined' ? data.rslt.cy : false;
      copied_node = (typeof $(data.rslt.oc).attr('id') != 'undefined') ? $(data.rslt.oc).attr('id').replace("node_", "") : 'UNDEFINED';
      new_parent_root = data.rslt.cr != -1 ? $(data.rslt.cr).attr('id').replace("node_", "") : 'root';
      replaced_node = (typeof $(data.rslt.or).attr('id') != 'undefined') ? $(data.rslt.or).attr('id').replace("node_", "") : 'UNDEFINED';
//                      console.log(data.rslt);
//                      console.log(pos,'POS');
//                      console.log(previous_node,'PREVIOUS NODE');
//                      console.log(moved_node,'MOVED_NODE');
//                      console.log(next_node,'NEXT_NODE');
//                      console.log(new_parent,'NEW PARENT');
//                      console.log(old_parent,'OLD PARENT');
//                      console.log(ref_node,'REFERENCE NODE');
//                      console.log(ot,'ORIGINAL TREE');
//                      console.log(rt,'REFERENCE TREE');
//                      console.log(copy,'IS IT A COPY');
//                      console.log( copied_node,'COPIED NODE');
//                      console.log( new_parent_root,'NEW PARENT INCLUDING ROOT');
//                      console.log(replaced_node,'REPLACED NODE');


      $.ajax({
        async: false,
        type: 'POST',
        url: Yii_js.baseUrl + "/" + JsTreeBehavior.controllerID + "/moveCopy",
        data: {
          "moved_node": moved_node,
          "new_parent": new_parent,
          "new_parent_root": new_parent_root,
          "old_parent": old_parent,
          "pos": pos,
          "previous_node": previous_node,
          "next_node": next_node,
          "copy": copy,
          "copied_node": copied_node,
          "replaced_node": replaced_node,
          "YII_CSRF_TOKEN": Yii_js.csrf
        },
        beforeSend: function() {
          spinner.spin(spinnertarget);
        },
        complete: function() {
          spinner.stop();
        },
        success: function(r) {
          response = $.parseJSON(r);
          if (!response.success) {
            $.jstree.rollback(data.rlbk);
            alert(response.message);
          }
          else {
//if it's a copy
            if (data.rslt.cy) {
              $(data.rslt.oc).attr("id", "node_" + response.id);
              if (data.rslt.cy && $(data.rslt.oc).children("UL").length) {
                data.inst.refresh(data.inst._get_parent(data.rslt.oc));
              }
            }
//  console.log('OK');
          }

        }
      }); //ajax
    }); //each function
  }); //bind move event

  ; //JSTREE FINALLY ENDS (PHEW!)

//BINDING EVENTS FOR THE ADD ROOT AND REFRESH BUTTONS.
  $("#add_root").click(function() {
    $.ajax({
      type: 'POST',
      url: Yii_js.baseUrl + "/" + JsTreeBehavior.controllerID + "/returnForm",
      data: {
        "create_root": true,
        "YII_CSRF_TOKEN": Yii_js.csrf
      },
      beforeSend: function() {
        spinner.spin(spinnertarget);
      },
      complete: function() {
        spinner.stop();
      },
      success: function(data) {
        $('#category-form').html(data);
      } //function

    }); //post
  }); //click function

  $("#reload").click(function() {
    jQuery("#" + JsTreeBehavior.container_ID).jstree("refresh");
  });
  $('#category-form').on('click', '#submit-category', function() {
    var action = $('#Category-form').attr('action');
    var name = $('#Category_name').val();
    var url = $('#Category_url').val();
    var seo = $('#Category_seo').val();
    var id = $('input[name="update_id"]').val();
    var parent_id = $('input[name="parent_id"]').val();
    var feature = [];
    $('table input:checkbox:checked').each(function (i, elm){
      feature[i]=(/\d+/).exec(elm.name)[0];
    });
    $.post(action, {
      Category: {name: name, url: url, seo: seo},
      update_id: id,
      parent_id: parent_id,
      feature:feature
    }, function(data) {
      var result = JSON && JSON.parse(data) || $.parseJSON(data);
      if (result.success) {
        if (!id) {
          id = result.id;
          if (parent_id)
            $('#node_' + parent_id + ' > ul > li.jstree-last').attr('id', 'node_' + id);
          else {
            jQuery.jstree._reference("#" + JsTreeBehavior.container_ID).create_node(-1, 'last', {attr: {id: 'node_' + id}, data: name});
            $('html, body').animate({scrollTop: $(document).height() - $(window).height()}, 'slow');
          }
          jQuery.jstree._reference("#" + JsTreeBehavior.container_ID).select_node('#node_' + id, true);
        }
        jQuery.jstree._reference("#" + JsTreeBehavior.container_ID).rename_node('#node_' + id, name);
//        $('#Category-form').attr('action', Yii_js.baseUrl + "/" + JsTreeBehavior.controllerID + '/updatenode');
//        $('#success-note').show();
      } else
        $('#error-note').show();
    });
  });
});