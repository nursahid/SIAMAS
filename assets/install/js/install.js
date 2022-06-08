;'use strict'; // recommended

alertify.defaults.glossary.title = "";

$(function() {

    $('.check').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue'
    });

    // variables
    var btnInstall = $('#btnInstall'),
        recommendation = '',
        form = $('form');

    // events
    btnInstall.bind('click keydown', do_install);

    // functions
    function do_install(event) {
        event.preventDefault();

        btnInstall.html('<i class="fa fa-circle-o-notch fa-spin"></i> Installing...').attr('disabled', true);

        $.ajax({
            url: base_url + 'install/do_install',
            data: form.serialize(),
            type: 'POST',
            dataType: 'json',
            success: do_install_request
        });
    }

    function do_install_request(response) {
        if (response.state === '1') {
            $.ajax({
                url: base_url + 'install/runMigration',
                type: 'POST',
                dataType: 'JSON',
                data: form.serialize(),
            }).done(function(data) {
                if (data.state === '1') {
                    $('.form-group').fadeOut();
                    btnInstall.fadeOut();
                    $('.login-box-body').html('<i class="fa fa-spin fa-circle-o-notch"></i> Processing...'); // preloader while migration finish
                    install_success();
                } else {
                    alertify.alert(data.msg);
                    btnInstall.removeAttr('disabled');
                    btnInstall.html('<i class="fa fa-download"></i> Install');
                }
            }).fail(function() {
                alertify.alert(data.msg);
                btnInstall.removeAttr('disabled');
                btnInstall.html('<i class="fa fa-download"></i> Install');
            });
        } else {
            alertify.alert(response.msg);
            btnInstall.removeAttr('disabled');
            btnInstall.html('<i class="fa fa-download"></i> Install');
        }
    }

    function install_success() {
        recommendation += '<b>Recommended steps after finish installation</b>';
        recommendation += '<ul>';
        recommendation += '<li>remove folder install from "application/modules/install"</li>';
        recommendation += '<li>remove folder install from "assets/install"</li>';
        recommendation += '</ul>';
        $('.login-box-body').html('<div class="alert alert-success text-center"><strong><i class="fa fa-check"></i> Installation Success</strong><br></div><div class="text-center"><a href="' + base_url + '" class="btn btn-primary">Go to main page</a></div><br/><div class="alert alert-warning">' + recommendation + '</div>');
    }

});
