/**
 * Documentor for XOOPS
 * Documentation system for XOOPS based on Common Utilities
 *
 * Copyright © 2014 Eduardo Cortés
 * -----------------------------------------------------------------
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 * -----------------------------------------------------------------
 * @package      Documentor
 * @author       Eduardo Cortés <yo@eduardocortes.mx>
 * @copyright    2009 - 2014 Eduardo Cortés
 * @license      GPL v2
 * @link         http://eduardocortes.mx
 * @link         http://xoopsmexico.net
 */

var contentNavigator = {
    navigate: function (url, pushState) {

        if ( url == '' || url == '#' )
            return false;

        $.get( url, {hideIndex:1}, function( response ){

            if ( response.type == 'error' ){

                $("#docs-resource-content").html('<div class="text-center"><span class="label label-danger">' + response.message + '</span></div>');
                return false;

            }

            if (pushState) {
                window.history.pushState({}, '', url);
            }

            $("html title").html(response.title);

            $("#docs-resource-content").html( response.content );

            // Added for Prism
            if( undefined != Prism ){
                $("pre > code").each(function(){

                    Prism.highlightElement($(this)[0], true);

                });
            }

            contentNavigator.navigation( response.id );

            $("#docs-resource-index a").trigger('update');

            contentNavigator.scrollToElement();

        }, 'json');

        return false;

    },
    navigation: function (t) {
        var e, o, n = $("#docs-resource-index ul li"),
            a = n.length,
            r = !1;
        for (i = 0; i < a; i++) {
            if (r) {
                o = $(n[i]).find("a").attr("href");
                break
            }

            // Display section list if hidden
            var element = $("#docs-resource-index ul li[data-section='" + t + "']");
            var parent = $(element).parents("li[data-parent='root']");

            if(parent.length > 0){

                if(!$(parent).hasClass('expanded')){
                    contentNavigator.expand($(parent));
                }

            }

            $(n).removeClass('current');
            $("#docs-resource-index ul li[data-section='" + t + "']").addClass('current');

            $(n[i]).data("section") == t ? r = !0 : e = $(n[i]).find("a").attr("href")
        }
        void 0 != e ? $(".docs-content-article .previous").attr("href", e) : $(".docs-content-article .previous").hide(), void 0 != o ? $(".docs-content-article .next").attr("href", o) : $(".docs-content-article .next").hide()
    },
    dialogMessage: function (t, e, o) {
        var i = $("#" + t + " .cu-dialog-content");
        if (i.length <= 0) return !1;
        var n = '<div class="docs-dialog-message alert alert-' + o + '"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + e + "</div>";
        i.find(".docs-dialog-message").remove(), i.append(n), i.find(".docs-dialog-message").fadeIn(250)
    },
    getSelection: function (t) {
        var e = document.getElementById(t),
            o = "";
        if ("selectionStart" in e) e.selectionStart != e.selectionEnd && (o = e.value.substring(e.selectionStart, e.selectionEnd));
        else {
            var i = document.selection.createRange(),
                n = i.parentElement();
            n === e && (o = i.text)
        }
        return o
    },
    insertContent: function (t, e) {
        if (myField = document.getElementById(t), document.selection) myField.focus(), sel = document.selection.createRange(), sel.text = e, myField.focus();
        else if (myField.selectionStart || "0" == myField.selectionStart) {
            var o = myField.selectionStart,
                i = myField.selectionEnd,
                n = myField.scrollTop;
            myField.value = myField.value.substring(0, o) + e + myField.value.substring(i, myField.value.length), myField.focus(), myField.selectionStart = o + e.length, myField.selectionEnd = o + e.length, myField.scrollTop = n
        } else myField.value += e, myField.focus()
    },
    insertIntoEditor: function (editor, type, text) {
        if ("tiny" == type) {
            if (void 0 == tinyMCE) return !1;
            var ed = tinyMCE.activeEditor;
            ed.execCommand("mceInsertContent", !0, text), ed.save()
        } else if ("markdown" == type) {
            if (void 0 == typeof mdEditor) return !1;
            mdEditor.insert(editor, text, this.getEditorSelection(editor, type))
        } else if ("html" == type) {
            if (void 0 == typeof edInsertContent) return !1;
            edInsertContent(editor, html)
        } else if ("simple" == type) {
            if ($("#" + editor).length <= 0) return !1;
            contentNavigator.insertContent(editor, html)
        } else if ("exmcode" == type) {
            var ed = eval("exmCode" + editor.charAt(0).toUpperCase() + editor.slice(1));
            ed.insertText(code)
        }
    },
    getEditorSelection: function (editor, type) {
        if ("tiny" == type) {
            if (void 0 == tinyMCE) return !1;
            var text = ed.selection.getContent();
            return text
        }
        if ("markdown" == type) {
            if (void 0 == typeof mdEditor) return !1;
            var currentText = mdEditor.selection(editor);
            return currentText
        }
        if ("html" == type) {
            if (void 0 == typeof edInsertContent) return !1;
            var text = edGetSelection(editor);
            return text
        }
        if ("simple" == type) {
            if ($("#" + editor).length <= 0) return !1;
            var text = contentNavigator.getSelection(editor);
            return text
        }
        if ("exmcode" == type) {
            var ed = eval("exmCode" + editor.charAt(0).toUpperCase() + editor.slice(1)),
                text = ed.selection().text;
            return text
        }
    },

    expand: function(item){

        if(undefined == $(item).data('parent') && 'root' != $(item).data('parent')){
            return;
        }

        var items = $(item).find('ul');

        if(items.length <= 0){
            return;
        }

        var visible = $("#docs-resource-index .docs-index .expanded").not($(item));
        if(visible.length > 0){
            $(visible).find('ul').slideUp('fast');
            $(visible).removeClass('expanded');
        }

        $(item).toggleClass('expanded');
        $(items).slideToggle('fast');

    },

    toggleSummary: function(){

        $("html").toggleClass("with-index");

        if($("html").hasClass('with-index')){
            $.cookie('docsindex', 1, {expires: 30, path: '/'});
        } else {
            $.cookie('docsindex', 0, {expires: 30, path: '/'});
        }

    },

    scrollToElement: function(){
        // Go to #
        var query = window.location.href.split("#");
        if(query.length != 2){
            return;
        }

        var id = query[1];
        if($("#" + id).length > 0){
            $("#docs-resource-content .docs-content-article").animate({
                scrollTop: $("#" + id).offset().top
            }, 1000);
        }
    }
};

$(document).ready(function () {

    window.onpopstate = function (t) {
        contentNavigator.navigate(window.location.href, !1)
    };

    $("body").on("click", "#docs-resource-index .docs-index a, .docs-content-inner a, .docs-content-article .document-navigation a", function () {

        if ("_blank" != $(this).attr("target")) {

            if ("external" == $(this).attr("rel")) return void $(this).attr("target", "_blank");

            if($(this).attr("href").substring(0, 1) == '#'){

                var id = $(this).attr("href");

                /*if($(id)){
                    $('#docs-resource-content > .docs-content-article').animate({
                        scrollTop: $(id).offset().top
                    }, 150);
                }*/

                $(".note-touched").removeClass('note-touched');

                if(id.substring(0, 5) == '#note'){
                    $(id).addClass('note-touched');
                }

                return false;
            }

            // Prevent navigation when local
            var href = document.createElement("a");
            href.href = $(this).attr('href');
            var url = document.createElement("a");
            url.href = window.location.href;
            var toSearch = url.origin + url.pathname + url.search;
            var left = $(this).attr('href').replace(toSearch, '');

            if(left != '' && left == href.hash){

                //window.history.pushState({}, '', $(this).attr('href'));

                $("#docs-resource-content .docs-content-article").animate({
                    scrollTop: $("#docs-resource-content .docs-content-article").scrollTop() + $(href.hash).offset().top
                }, 1000);

                return false;
            }

            var t = docsUrl.replace(xoUrl, "");
            return t != $(this).attr("href").substring(0, t.length) && -1 >= $(this).attr("href").indexOf(docsUrl) ? void $(this).attr("target", "_blank") : (contentNavigator.navigate($(this).attr("href"), !0), !1)
        }
    });

    contentNavigator.navigate(window.location.href, !1);

    $(".note-link").click(function () {
        var t = $(this).attr("href").replace("#note-", "");
        $("#note-" + t).effect("highlight", {}, 5e3)
    }), $("body").on("click", ".toggle-summary", function () {
        return contentNavigator.toggleSummary(), !1
    }), $("body").on("click", ".toggle-align", function () {
        var t = "align-" + $(this).data("align");
        if (!$("html").hasClass(t)) return $("html").removeClass("align-left align-center align-justify").addClass(t), $.cookie("docu_align", $(this).data("align"), {
            expires: 30,
            path: "/"
        }), !1
    }), $("body").on("click", "#dialog-search", function () {
        var t = $("#dialog-uname").val();
        if ("" == t) return $("#dialog-uname").focus().parent().addClass("has-error"), $(this).removeClass("btn-default").addClass("btn-danger"), contentNavigator.dialogMessage("link-dialog", docsLang.errorUser, "danger"), !1;
        $("#dialog-uname").focus().parent().removeClass("has-error"), $(this).addClass("btn-default").removeClass("btn-danger"), $(this).find(".fa").removeClass("fa-search").addClass("fa-spinner fa-pulse");
        var e = {
            CUTOKEN_REQUEST: $("#cu-token").val(),
            user: t,
            action: "link-resources",
            editor: $("#link-dialog .sections-list").data("editor"),
            type: $("#link-dialog .sections-list").data("type")
        };
        $.post("sections.php", e, function (t) {
            cuHandler.retrieveAjax(t) && $("#link-dialog .modal-body").html(t.content)
        }, "json")
    }), $("body").on("click", "#link-dialog .books-list a", function () {
        var t = $(this).data("id"),
            e = $(this).data("owner"),
            o = $(this);
        if (0 >= t || void 0 == t || void 0 == e || "" == e) return contentNavigator.dialogMessage("link-dialog", docsLang.errorData, "danger"), !1;
        var i = {
            user: e,
            id: t,
            CUTOKEN_REQUEST: $("#cu-token").val(),
            action: "link-resources",
            editor: $("#link-dialog .sections-list").data("editor"),
            type: $("#link-dialog .sections-list").data("type")
        };
        o.find(".icon-book").removeClass("icon icon-book").addClass("fa fa-spinner fa-pulse"), $.post("sections.php", i, function (t) {
            cuHandler.retrieveAjax(t) && $("#link-dialog .modal-body").html(t.content);
        }, "json");
        return false;
    }), $("body").on("click", "#link-dialog .sections-list a", function () {
        var link = $(this).data("link"),
            title = $(this).data("title");
        if (void 0 == link || "" == link) return !1;
        var type = $("#link-dialog .sections-list").data("type"),
            editor = $("#link-dialog .sections-list").data("editor");
        if (void 0 == type || "" == type || void 0 == editor || "" == editor) return contentNavigator.dialogMessage("link-dialog", docsLang.noType, "danger"), !1;
        if ("tiny" == type) {
            if (void 0 == tinyMCE) return contentNavigator.dialogMessage("link-dialog", docsLang.errorMD, "danger"), !1;
            var ed = tinyMCE.activeEditor,
                html = '<a href="' + link + '">%s</a>',
                text = ed.selection.getContent();
            html = void 0 == text || "" == text ? html.replace("%s", title) : html.replace("%s", text), ed.execCommand("mceInsertContent", !0, html), ed.save()
        } else if ("markdown" == type) {
            if (void 0 == typeof mdEditor) return contentNavigator.dialogMessage("link-dialog", docsLang.errorMD, "danger"), !1;
            var currentText = mdEditor.selection(editor),
                md = "[%](" + link + ")";
            mdEditor.insert(editor, md, title)
        } else if ("html" == type) {
            if (void 0 == typeof edInsertContent) return contentNavigator.dialogMessage("link-dialog", docsLang.errorMD, "danger"), !1;
            var text = edGetSelection(editor),
                html = '<a href="' + link + '">%s</a>';
            html = void 0 == text || "" == text ? html.replace("%s", title) : html.replace("%s", text), edInsertContent(editor, html)
        } else if ("simple" == type) {
            if ($("#" + editor).length <= 0) return contentNavigator.dialogMessage("link-dialog", docsLang.errorMD, "error"), !1;
            var text = contentNavigator.getSelection(editor),
                html = '<a href="' + link + '">%s</a>';
            html = void 0 == text || "" == text ? html.replace("%s", title) : html.replace("%s", text), contentNavigator.insertContent(editor, html)
        } else if ("exmcode" == type) {
            var ed = eval("exmCode" + editor.charAt(0).toUpperCase() + editor.slice(1)),
                text = ed.selection().text,
                code = "[url=" + link + "]%s[/url]";
            code = void 0 == text || "" == text ? code.replace("%s", title) : code.replace("%s", text), ed.insertText(code)
        }
        $("#link-dialog").modal("hide")
    }), $("body").on("click", "#notes-dialog .dialog-commands a.show-creator, #notes-dialog .notes-creator .btn-default", function () {
        $("#notes-dialog").toggleClass("creator"), $("#notes-dialog .notes-creator .note-content").focus()
    }), $("body").on("click", "#notes-dialog .notes-creator .create-note", function () {
        var t = $("#notes-dialog .notes-creator .note-content");
        if (t.length <= 0) return contentNavigator.dialogMessage("notes-dialog", docsLang.invalidNote, "danger"), !1;
        if ("" == t.val()) return contentNavigator.dialogMessage("notes-dialog", docsLang.missingNote, "danger"), !1;
        var e = {
            text: t.val(),
            CUTOKEN_REQUEST: $("#cu-token").val(),
            action: "save-note",
            id: $("#notes-dialog .notes-creator").data("id")
        };
        $.post("sections.php", e, function (t) {
            if (cuHandler.retrieveAjax(t)) {
                var e = $("#notes-dialog .dialog-commands").data("editor"),
                    o = $("#notes-dialog .dialog-commands").data("type");
                contentNavigator.insertIntoEditor(e, o, "[note id=" + t.note + "] "), $("#notes-dialog").modal("hide")
            }
        }, "json")
    }), $("body").on("click", "#notes-dialog .notes-list .insert-note", function () {
        var t = $(this).data("id");
        if (void 0 == t || 0 >= t) return !1;
        var e = $("#notes-dialog .dialog-commands").data("editor"),
            o = $("#notes-dialog .dialog-commands").data("type");
        contentNavigator.insertIntoEditor(e, o, "[note id=" + t + "] "), $("#notes-dialog").modal("hide")
    }), $("body").on("click", "#notes-dialog a[data-page]", function () {
        var t = $(this).data("page");
        if (0 >= t) return !1;
        var e = $("#notes-dialog .dialog-commands").data("editor"),
            o = $("#notes-dialog .dialog-commands").data("type"),
            i = $("#notes-dialog .dialog-commands").data("id"),
            n = {
                id: i,
                page: t,
                editor: e,
                type: o,
                CUTOKEN_REQUEST: $("#cu-token").val(),
                action: "insert-notes"
            };
        return $(this).html('<span class="fa fa-spinner fa-spin"></span>'), $.post("sections.php", n, function (t) {
            return cuHandler.retrieveAjax(t) ? ($("#notes-dialog .modal-body").html(t.content), !1) : void 0
        }, "json"), !1
    }), $("body").on("click", "#notes-dialog .search-box .btn", function () {
        var t = $("#notes-dialog .search-box > input").val();
        if (void 0 == t || "" == t) return !1;
        var e = $("#notes-dialog .dialog-commands").data("editor"),
            o = $("#notes-dialog .dialog-commands").data("type"),
            i = $("#notes-dialog .dialog-commands").data("id"),
            n = {
                id: i,
                search: t,
                editor: e,
                type: o,
                CUTOKEN_REQUEST: $("#cu-token").val(),
                action: "insert-notes"
            };
        return $(this).html('<span class="fa fa-spinner fa-spin"></span>'), $.post("sections.php", n, function (t) {
            return cuHandler.retrieveAjax(t) ? ($("#notes-dialog .modal-body").html(t.content), !1) : void 0
        }, "json"), !1
    });

    if ($("#docs-resource-index").length){

        $("#docs-resource-index").perfectScrollbar();

    }

    // Display subibdex
    $("#docs-resource-index .docs-index li a").click(function(){

        contentNavigator.expand($(this).parent());

    });

    $("#docs-resource-index .quick-search input").keyup(function(e){

        if(e.which != 13){
            return false;
        }

        if($(this).val().length < 3){
            $("#docs-resource-index > .no-results").slideUp('fast');
            $("#docs-resource-index > .results-index").remove();
            $("#docs-resource-index > .docs-index").fadeIn('fast');
            return false;
        }

        var filter = $(this).val();
        var results = [];

        $("#docs-resource-index ul > li > a").each(function(){
            // If the list item does not contain the text phrase fade it out
            if ($(this).text().search(new RegExp(filter, "i")) >= 0){
                results[results.length] = $(this).clone();
            }
        });

        if(results.length <= 0){
            $("#docs-resource-index > .no-results").slideDown('fast');
            return false;
        }

        $("#docs-resource-index > .docs-index").fadeOut('fast');

        var list = $("<ul/>")
            .addClass('results-index');

        for(var i=0; i<results.length;i++){
            $(list).append(results[i]);
        }

        $("#docs-resource-index").append(list);

    });
});
