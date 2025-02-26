function setRows(champ) {
    var arg = champ.val();
    var nbr_line = 1;
    var nbr_char_line = 0;
    var nbr_char_on_line = 68;
    for (var i = 0; i < arg.length; i++) {
        if (arg.charCodeAt(i) == 10) {
            nbr_line++;
            nbr_char_line = 0;
        } else {
            nbr_char_line++;
            if (nbr_char_line > nbr_char_on_line) {
                nbr_line++;
                nbr_char_line = 1;
            }
        }
    }
    nbr_line++;
    nbr_line++;
    nbr_line++;
    nbr_line++;
    champ.attr("rows", nbr_line++);

}

function message(url, message) {
    $.ajax({
        type: "post",
        url: url,
        data: "message=" + message,
        success: function (t) {
            $(".alertType").stop(true);
            $(".alertType").show("0");
            $(".alertType").html(t);
            $(".alertType").delay("2500").hide("0");
        }});
}

function couleurAlerteClass(element, css) {
    $(element).stop(true);
    $(element).addClass(css);
    setTimeout(function () {
        $(element).removeClass(css);
    }, 5000);
}

function couleurAlerteCss(element, css, cssOrigine) {
    $(element).stop(true);
    $(element).css(css);
    setTimeout(function () {
        $(element).css(cssOrigine);
    }, 5000);
}

function sendMail(button) {
    button.val("Envoi en cours ...");
    button.prop('onclick', null).off('click');

    $.ajax({
        type: "post",
        url: urlSendMail,
        data: "mail=" + button.data("mail"),
        success: function (t) {
            if (t === "1") {
                message(urlSucces, "Un email à été envoyé.");
            } else {
                message(urlError, "un probleme est survenu veuillez actualiser la page et reessayer.");
            }
            button.val("Renvoyer le mail");
            sendMailClick();
        }});
}

function addNewletter(button) {
    button.val("");
    button.prop('onclick', null).off('click');

    $.ajax({
        type: "get",
        url: urlAddMailNewsletter,
        data: "mail=" + $(".inputButtonNewsletter").val(),
        success: function (t) {
            $(".resultNewsletter").html(t);
            $(".inputButtonNewsletter").val("")
            button.val("OK");
            sendAddNewsletter();
        }});
}

function sendMailClick() {
    $(".sendMail").on("click", function () {
        sendMail($(this));
    });
}

function sendAddNewsletter() {
    $(".buttonNewsletter").on("click", function () {
        var reg = new RegExp('^[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*@[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*[\.]{1}[a-z]{2,6}$', 'i');
        $('.mess_required').remove();
        if (!reg.test($(".inputButtonNewsletter").val())) {
            $($('.resultNewsletter').parent()).append("<span class='mess_required'>Ceci n'est pas un email</span>");
        } else {
            addNewletter($(this));
        }
    });
}

$(document).ready(function () {
    mess_required = "<span class='mess_required'>Ce champ est obligatoire.</span>";
    mdp_required = "<span class='mess_required'>Les mots de passe sont différents.</span>";
    mail_required = "<span class='mess_required'>Les mails sont différents.</span>";
    mdp_identique_required = "<span class='mess_required'>Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre.</span>";
    login_insuffisant = "<span class='mess_required'>Le nom d'utilisateur doit être compris entre 6 et 50 caratères</span>";

    var iniPopUp = 0;

    sendMailClick();
    sendAddNewsletter();

    $('body').click(function (e) {
        if (e.target.id === "popUpConnexion") {
            if (iniPopUp === 0) {
                $('.connexion_popin').show();
                iniPopUp = 1;
            } else {
                $('.connexion_popin').hide();
                iniPopUp = 0;
            }
        } else {
            if ($(e.target).closest('.connexion_popin').length === 0) {
                if (iniPopUp === 1) {
                    $('.connexion_popin').hide();
                    iniPopUp = 0;
                }
            }
        }

    });

    //popup login connexion
    $('#popup_input_connexion').click(function () {
        $('.connexion_popin .login span.mess_required').remove();
        $('.connexion_popin .login input.failed').removeClass("failed");
        var submit = true;
        $('.connexion_popin .login input.required').each(function () {
            if ($(this).val() == '') {
                $($(this).parent()).append(mess_required);
                $($(this)).toggleClass('failed');
                submit = false;
            }
        });
        return submit;
    });

    //popup login inscription
    $('#popup_login_inscription').click(function () {
        $('.connexion_popin .bottom span.mess_required').remove();
        $('.connexion_popin .bottom input.failed').removeClass("failed");
        var submit = true;
        $('.connexion_popin .bottom input.required').each(function () {
            if ($(this).val() == '') {
                $($(this).parent()).append(mess_required);
                $($(this)).toggleClass('failed');
                submit = false;
            }
        });
        return submit;
    });

    //page inscription
    $('#bouton_page_inscription').click(function () {
        $('.content-inscription span.mess_required').remove();
        $('.content-inscription input.failed').removeClass("failed");
        var submit = true;
        $('.content-inscription input.required').each(function () {
            if ($(this).val() == '') {
                $($(this).parent()).append(mess_required);
                $(this).addClass('failed');
                submit = false;
            }
        });

        if (submit) {
            if ($(".content-inscription input.mail").val() != $(".content-inscription input.cmail").val()) {
                $(".content-inscription .une_row.cmail").append(mail_required);
                $(".content-inscription .une_row.mail p").toggleClass('failed');
                submit = false;
            }
        }

        if (submit) {
            if ($(".content-inscription input.mdp").val() != $(".content-inscription input.cmdp").val()) {
                $(".content-inscription .une_row.cmdp").append(mdp_required);
                $(".content-inscription .une_row.mdp input").toggleClass('failed');
                submit = false;
            }

            var regex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/;
            if (!regex.test($(".content-inscription input.mdp").val())) {
                $(".content-inscription .une_row.cmdp").append(mdp_identique_required);
                $(".content-inscription .une_row.mdp input").toggleClass('failed');
                submit = false;
            }
        }
        return submit;
    });

    //page connexion
    $('#input_page_connexion').click(function () {
        $('.content-connexion span.mess_required').remove();
        $('.content-connexion input.failed').removeClass("failed");
        var submit = true;
        $('.content-connexion input.required').each(function () {
            if ($(this).val() == '') {
                $($(this).parent()).append(mess_required);
                $($(this)).toggleClass('failed');
                submit = false;
            }
        });
        return submit;
    });

    //page contact
    $('#bouton_envoi_contact').click(function () {
        $('.content-contact span.mess_required').remove();
        $('.content-contact span.mess_required').remove();
        $('.content-contact p.failed').removeClass("failed");
        $('.content-contact textarea.failed').removeClass("failed");
        var submit = true;
        $('.content-contact input.required').each(function () {
            if ($(this).val() == '') {
                $($(this).parent().parent()).append(mess_required);
                $($(this).parent()).toggleClass('failed');
                submit = false;
            }
        });

        if ($('.content-contact textarea.required').val() == '') {
            $($('.content-contact textarea.required').parent()).append(mess_required);
            $($('.content-contact textarea.required')).toggleClass('failed');
            submit = false;
        }

        return submit;
    });

    // Page mot de passe oublié
    $('#input_page_password').click(function () {
        $('.content-motdepass span.mess_required').remove();
        $('.content-motdepass input.failed').removeClass("failed");
        var submit = true;
        $('.content-motdepass input.required').each(function () {
            if ($(this).val() == '') {
                $($(this).parent()).append(mess_required);
                $($(this)).toggleClass('failed');
                submit = false;
            }
        });
        return submit;
    });

    $('.ancreVoyage').click(function () {
        $("body").animate({scrollTop: ($('#contenu_home_ancre').offset().top)}, 1000, 'easeInOutCubic');
    });


});

$(window).load(function () {
    $(".chargement").hide();
    $(".content").hide();
    $(".content").css({"visibility": "visible"});
    $(".content").fadeIn("slow");
});