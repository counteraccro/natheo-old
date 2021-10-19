/**
 *  JS gestion des users
 *  @author Gourdon Aymeric
 *  @version 1.0
 **/
let User = {}

User.Launch = function () {

    User.globalId = '#admin-user-globale';

    /**
     * Charge la liste des users
     * @constructor
     */
    User.LoadListing = function () {
        let id = User.globalId + ' .card-body';
        let url = $(id).data('url');

        let str_loading = $(id).data('loading');
        System.Ajax(url, id, true, str_loading);
    };

    User.CreateUpdate = function() {
        User.createUpdateId = '#admin-create-update-user';

        /**
         * Event sur le champs password 1
         */
        $(User.createUpdateId + " #password_1").keyup(function() {
            User.checkStrengthPassword($(this).val());
        });

        /**
         * Génération d'un mot de passe aléatoire
         */
        $(User.createUpdateId + " #generate-password").click(function() {

            pass = System.GeneratePassword();
            $(User.createUpdateId + " #password_1").val(pass);
            User.checkStrengthPassword(pass)
            $(User.createUpdateId + " #password_2").val(pass);

            return false;

        });

        /**
         * Affiche ou masque le mot de passe
         */
        $(User.createUpdateId + " #show-password").click(function() {
            if($(User.createUpdateId + " #password_1").attr('type') === "password")
            {
                $(User.createUpdateId + " #password_1").attr('type', 'text');
                $(this).children().removeClass('fa-eye').addClass('fa-eye-slash');
            }
            else {
                $(User.createUpdateId + " #password_1").attr('type', 'password');
                $(this).children().removeClass('fa-eye-slash').addClass('fa-eye');
            }
        })
    }

    /**
     * Permet de vérifier la force d'un mot de passe
     * @param password
     * @returns {string|boolean}
     */
    User.checkStrengthPassword = function (password) {
        let element = $('#admin-create-update-user #strength-password-info');

        let strength = 0

        if (password.length === 0) {
            element.removeClass('bg-success bg-warning bg-danger').css({"width": "0%"});
            return false;
        }

        if (password.length < 6) {
            element.removeClass('bg-success bg-warning bg-danger').addClass('bg-danger').css({"width": "25%"}).html(element.data('weak'));
            return false;
        }

        if (password.length > 7) strength += 1
        if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) strength += 1
        if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) strength += 1
        if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1
        if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1
        if (strength < 2) {
            element.removeClass('bg-success bg-warning bg-danger').addClass('bg-danger').css({"width": "25%"}).html(element.data('weak'));
            return false;
        } else if (strength === 2) {
            element.removeClass('bg-success bg-warning bg-danger').addClass('bg-warning').css({"width": "50%"}).html(element.data('normal'));
            return false;
        } else {
            element.removeClass('bg-success bg-warning bg-danger').addClass('bg-success').css({"width": "100%"}).html(element.data('strong'));
            return false;
        }
    }
}