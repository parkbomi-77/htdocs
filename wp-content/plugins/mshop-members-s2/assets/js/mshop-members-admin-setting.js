jQuery(document).ready(function(a){a("select#mshop_members_unsubscribe_after_process").change(function(){"none"===a(this).val()?a(this).parent().parent().next("tr").show():a(this).parent().parent().next("tr").hide()}).change()});