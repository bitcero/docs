function rd_check_delete(e, t) {
    return 0 >= e ? !1 : ($("#" + t + " input[type=checkbox]").removeAttr("checked"), $("#item-" + e).attr("checked", "checked"), $("#bulk-top").val("delete"), void before_submit(t))
}

function before_submit(e) {
    var t = $("#" + e + " input[name='ids[]']"),
        o = !1;
    for (i = 0; i < t.length; i++) $(t[i]).is(":checked") && (o = !0);
    return o ? void("delete" == $("#bulk-top").val() ? confirm(rd_message) && $("#" + e).submit() : $("#" + e).submit()) : (alert(rd_select_message), !1)
}

function rd_show_figure_editor() {
    $("#rd-figures-editor").show("fast")
}
$(document).ready(function() {
    $("#bulk-top").change(function() {
        $("#bulk-bottom").val($(this).val())
    }), $("#bulk-bottom").change(function() {
        $("#bulk-top").val($(this).val())
    }), $("#the-op-top").click(function() {
        $("#frm-resources").submit()
    })
});