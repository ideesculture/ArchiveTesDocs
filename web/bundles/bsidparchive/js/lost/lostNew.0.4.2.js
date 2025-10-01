/**
 * Created by Cyril on 09/11/2015.
 */

$(document).ready(function() {
});

$('#btnImLost').on('click', function(e){
    e.preventDefault();

    $('#archives').collapse({'toggle': false});
    $('#fournitures').collapse({'toggle': false});
    if( window.IDP_CONST.bs_idp_userscale < 100 ) {
        $('#gestion').collapse({'toggle':false});
    }

    var _slides = [];
    _slides.push({
        /* 1 */
        content: "Vous vous trouvez actuellement sur la page de saisie. Ici, vous pouvez enregistrer les informations descriptives d'une unité d'archives avant d'effectuer une demande de transfert auprès de l'archiviste.",
        selector: 'html',
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'all'
    });
    _slides.push({
        /* 2 */
        content: "Voici les différentes rubriques de la page d'accueil. La partie dans laquelle vous vous trouvez est indiquée grâce à une coloration bleu clair. Vous pouvez à tout moment vous déplacer dans une autre partie du logiciel en cliquant sur une rubrique différente.",
        selector: '#left-menu',
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',
        position: 'right-center'
       });
    _slides.push({
        /* 3 */
        content: "Vous pouvez accéder à la page d'accueil en cliquant sur ce bouton.",
        selector: '#btnAccueil',
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',
        position: 'right-top'
    });
    _slides.push({
        /* 4 */
        content: "Cette rubrique concerne les demandes accessibles à l'utilisateur.",
        selector: '#btnArchives', // html
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',   // all
        position: 'right-center'
    });
    _slides.push({
        /* 5 */
        content: "Cette rubrique concerne exclusivement la gestion des fournitures, que ce soit au niveau de l'utilisateur ou au niveau de l'archiviste.",
        selector: '#btnFournitures', // html
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',   // all
        position: 'right-center'
    });

    if( window.IDP_CONST.bs_idp_userscale < 100 ) {
        _slides.push({
            /* 6 */
            content: "Cette rubrique concerne toutes les fonctions accessibles à l'archiviste.",
            selector: '#btnGestion', // html
            title: "Archimage - Je suis perdu(e)",
            overlayMode: 'focus',   // all
            position: 'right-center'
        });
    }

    _slides.push({
        /* 7 */
        content: "Voici la fiche descriptive de l'unité d'archives. Tous les champs comportant <span class='text-danger'>*</span> sont obligatoires, les autres sont facultatifs.",
        selector: '#frmUA', // html
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',   // all
        position: 'left-center'
    });
    _slides.push({
        /* 8 */
        content: "C'est un numéro unique attribué automatiquement à chaque unité d'archives. Il permet de vous identifier en tant que créateur et de retrouver facilement vos documents.",
        selector: '#form_ordernumber', // html
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',   // all
        position: 'bottom-center'
    });
    _slides.push({
        /* 9 */
        content: "Cette partie comprend les informations concernant le propriétaire de l'unité d'archives.",
        selector: '#zoneProprietaire', // html
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',   // all
        position: 'right-top'
    });
    _slides.push({
        /* 10 */
        content: "Il s'agit du ou des service(s) pour lesquels vous travaillez.",
        selector: '#divSelectService', // html
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',   // all
        position: 'right-center'
    });
    _slides.push({
        /* 11 */
        content: "Il s'agit de l'entité légale à laquelle appartient l'unité d'archives.",
        selector: '#divSelectLegalentity', // html
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',   // all
        position: 'right-center'
    });
    if( $_settings.view_budgetcode ) {
        _slides.push({
            /* 12 */
            content: "Il s'agit du code budgétaire auquel est rattachée l'unité d'archives.",
            selector: '#divSelectBudgetcode', // html
            title: "Archimage - Je suis perdu(e)",
            overlayMode: 'focus',   // all
            position: 'right-center'
        });
    }
    if( $_settings.view_documentnature || $_settings.view_description1 || $_settings.view_description2 ){
        _slides.push({
            /* 13 */
            content: "Cette partie comprend les informations descriptives de l'unité d'archives.",
            selector: '#zoneDescriptives', // html
            title: "Archimage - Je suis perdu(e)",
            overlayMode: 'focus',   // all
            position: 'right-top'
        });
        if( $_settings.view_documentnature ){
            _slides.push({
                /* 14 */
                content: "Il s'agit du type d'activité/métier auquel appartient l'unité d'archives.",
                selector: '#divSelectDocumentnature', // html
                title: "Archimage - Je suis perdu(e)",
                overlayMode: 'focus',   // all
                position: 'right-center'
            });
            if( $_settings.view_documenttype ){
                _slides.push({
                    /* 15 */
                    content: "Il s'agit du type de dossier/document de l'activité sélectionnée précédemment. Si votre entreprise dispose d'une charte d'archivage, le calcul de la durée de conservation du document pourra se faire automatiquement.",
                    selector: '#divSelectDocumenttype', // html
                    title: "Archimage - Je suis perdu(e)",
                    overlayMode: 'focus',   // all
                    position: 'right-center'
                });
            }
        }
        if( $_settings.view_description1 || $_settings.view_description2 ){
            _slides.push({
                /* 16 et 17 */
                content: "Cette partie comprend les informations descriptives de l'unité d'archives.",
                selector: '#zoneDescription', // html
                title: "Archimage - Je suis perdu(e)",
                overlayMode: 'focus',   // all
                position: 'right-center'
            });
        }
    }
    _slides.push({
        /* 18 */
        content: "Cette partie comprend les éléments relatifs à la durée de conservation de l'unité d'archives.",
        selector: '#zoneDuree', // html
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',   // all
        position: 'right-center'
    });
    _slides.push({
        /* 19 */
        content: "Elle correspond à la date/l'année à partir de laquelle débute la durée de conservation légale de l'unité d'archives.",
        selector: '#divInputClosureyear', // html
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',   // all
        position: 'right-center'
    });
    _slides.push({
        /* 20 */
        content: "Elle correspond à la date/l'année de destruction possible de l'unité d'archives (elle doit cependant respecter la durée légale de conservation).",
        selector: '#divInputDestructionyear', // html
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',   // all
        position: 'right-center'
    });

    if( $_settings.view_filenumber || $_settings.view_boxnumber || $_settings.view_containernumber || $_settings.view_provider ) {
        _slides.push({
            /* 21 */
            content: "Cette partie comprend les éléments permettant d'identifier le prestataire d'archivage.",
            selector: '#zonePrestataire', // html
            title: "Archimage - Je suis perdu(e)",
            overlayMode: 'focus',   // all
            position: 'right-center'
        });
        if( $_settings.view_filenumber ){
            _slides.push({
                /* 22 */
                content: "Il s'agit du numéro de code-barres prestataire apposé sur le document ou le dossier à archiver ou d'un numéro provisoire.",
                selector: '#divInputFilenumber', // html
                title: "Archimage - Je suis perdu(e)",
                overlayMode: 'focus',   // all
                position: 'right-center'
            });
        }
        if( $_settings.view_boxnumber ){
            _slides.push({
                /* 23 */
                content: "Il s'agit du numéro de code-barres prestataire apposé sur la boite d'archives ou d'un numéro provisoire.",
                selector: '#divInputBoxnumber', // html
                title: "Archimage - Je suis perdu(e)",
                overlayMode: 'focus',   // all
                position: 'right-center'
            });
        }
        if( $_settings.view_containernumber ){
            _slides.push({
                /* 24 */
                content: "Il s'agit du numéro de code-barres prestataire apposé sur le conteneur d'archives ou d'un numéro provisoire.",
                selector: '#divInputContainernumber', // html
                title: "Archimage - Je suis perdu(e)",
                overlayMode: 'focus',   // all
                position: 'right-center'
            });
        }
        if( $_settings.view_provider ){
            _slides.push({
                /* 25 */
                content: "Il s'agit du code client prestataire chez lequel l'unité d'archives sera envoyée.",
                selector: '#divSelectProvider', // html
                title: "Archimage - Je suis perdu(e)",
                overlayMode: 'focus',   // all
                position: 'right-center'
            });
        }
    }

    _slides.push({
        /* 26 */
        content: "Il s'agit de la description de l'unité d'archives. Il est préférable qu'elle soit concise et suffisamment précise, pour que vous retrouviez facilement vos documents.",
        selector: '#frm_name', // html
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',   // all
        position: 'left-center'
    });

    if( $_settings.view_limitsdate || $_settings.view_limitsalpha || $_settings.view_limitsnum || $_settings.view_limitsalphanum ) {
        _slides.push({
            /* 27 */
            content: "Cette partie permet de borner l'unité d'archives.",
            selector: '#div2Limits', // html
            title: "Archimage - Je suis perdu(e)",
            overlayMode: 'focus',   // all
            position: 'left-center'
        });
        if( $_settings.view_limitsdate ){
            _slides.push({
                /* 28 */
                content: "Il s'agit d'un intervalle de dates. Un calendrier vous est proposé pour sélectionner vos dates.",
                selector: '#zoneLimitDate', // html
                title: "Archimage - Je suis perdu(e)",
                overlayMode: 'focus',   // all
                position: 'left-center'
            });
        }
        if( $_settings.view_limitsnum ){
            _slides.push({
                /* 29 */
                content: "Il s'agit d'un intervalle numérique.",
                selector: '#zoneLimitNum', // html
                title: "Archimage - Je suis perdu(e)",
                overlayMode: 'focus',   // all
                position: 'left-center'
            });
        }
        if( $_settings.view_limitsalpha ){
            _slides.push({
                /* 30 */
                content: "Il s'agit d'un intervalle alphabétique.",
                selector: '#zoneLimitAlpha', // html
                title: "Archimage - Je suis perdu(e)",
                overlayMode: 'focus',   // all
                position: 'left-center'
            });
        }
        if( $_settings.view_limitsalphanum ){
            _slides.push({
                /* 31 */
                content: "Il s'agit d'un intervalle alphanumérique.",
                selector: '#zoneLimitAlphanum', // html
                title: "Archimage - Je suis perdu(e)",
                overlayMode: 'focus',   // all
                position: 'left-center'
            });
        }
    }

    _slides.push({
        /* 32 */
        content: "Ce bouton vous permet de valider définitivement la saisie de l'unité d'archives et de l'envoyer dans la partie 'Transférer'. Une partie des informations seront sauvegardées pour faciliter la saisie de votre prochaine unité d'archives.",
        selector: '#divValidate', // html
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',   // all
        position: 'top-center'
    });

    _slides.push({
        /* 33 */
        content: "Ce bouton vous permet d'imprimer la fiche de saisie de l'unité d'archives.",
        selector: '#divPrint', // html
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',   // all
        position: 'top-center'
    });

    _slides.push({
        /* 34 */
        content: "Ce bouton vous permet d'imprimer l'étiquette autocollante reprenant les informations de l'unité d'archives. Vous pourrez ensuite l'apposer dessus.",
        selector: '#divPrintTag', // html
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',   // all
        position: 'top-center'
    });


    $.tutorialize({
        slides: _slides,
        showClose: true,
        keyboardNavitation: true,
        labelClose: 'Fermer',
        labelEnd: 'Fin',
        labelNext: 'Suivant',
        labelPrevious: 'Précédent',
        labelStart: 'Commencer',
        arrowPath: '/img/arrow-blue.png',
        onStart: function(){
            $('#fournitures').collapse('show');
            if( window.IDP_CONST.bs_idp_userscale < 100 ) {
                $('#gestion').collapse('show');
            }
        },
        onStop: function(){
            $('#fournitures').collapse('hide');
            if( window.IDP_CONST.bs_idp_userscale < 100 ) {
                $('#gestion').collapse('hide');
            }
        }
    });

    $.tutorialize.start();

    $('#archives').collapse({'toggle': true});
    $('#fournitures').collapse({'toggle': true});
    if( window.IDP_CONST.bs_idp_userscale < 100 ) {
        $('#gestion').collapse({'toggle':true});
    }

});