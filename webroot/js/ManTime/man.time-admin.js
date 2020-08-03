$(function () {
    // init();
    $(".users").change();
    alert = false;
    alertVerouillage = false;
    updateTotal();
    first = false;
    add = false;
});

var arrayDays = ["Lu", "Ma", "Me", "Je", "Ve", "Sa", "Di"];
var alertVerouillage;
var first = true;
var unice = true;
var premierPassage = true;

// function init() {

    $("select").each(function () {
        var selected = $(this).val();
        $(this)
            .children("option [value='" + selected + "']")
            .attr("selected", "selected");
    });

    $("form").on("submit", function (e) {
        var that = this;
        // plus d'envoie standard du formulaire
        e.preventDefault();
        e.stopPropagation();


        if ($("#validat").prop("checked")) {
            var modal = new ModalWindow({
                Title: "Validation semaine",
                Message:
                    "Vous avez coché la validation pour export, les consultants ne pourront plus modifier leur temps. Êtes-vous sûr de vouloir continuer ?",
                Buttons: [
                    ["btn-danger admin", "Non", "false"],
                    ["btn-primary admin", "Oui", "true"],
                ],
                CallBack: function (result, event, formData, ExtraData, rootDiv) {
                    if (result === "true") {
                        alertVerouillage = false;
                        sendOnlyChange(that);
                    } else {
                        $("#validat").prop("checked", false);
                        alertVerouillage = true;
                    }
                    // $("form").submit();
                    alertVerouillage = $("#validat").prop("checked");
                },
                Center: true,
                AllowClickAway: false,
            });
            modal.Show();
        }

        //// EVOL : sauvegarde des modification \\\\
        if (!alertVerouillage) {
            sendOnlyChange(that);
        }
    });


// }

    $("#validat").click(function () {
        alertVerouillage = $("#validat").prop("checked");
    });

    $(".remove").click(function () {
        delLine(this);
        chkActivitie();
    });

    $("#add").click(function () {
        addLine(this);
        chkActivitie();
    });

    $(".users").change(function () {
        modifyUser(this);
    });

    $(".client").change(function () {
        modifyClient(this);
    });

    $(".project").change(function () {
        modifyProject(this);
    });

    $(".profil").change(function () {
        modifyProfil(this);
    });

    $(".activit").change(function () {
        modifyActivite(this);
    });

    $(".numericer").on("input", function () {
        numericer(this);
        updateTotal();
    });

function chkActivitie(){
    $.ajax({
        type: "GET",
        url: "/users/cksession/",
    }).done(function (data) {
        if (!data) {
            //fail (success : no effectt)
            document.location.replace("/users/login");
        }
    });
}

function sendOnlyChange(form){
    // Ancien formdata (envoyé pas le form)
    var oldFormData = new FormData(form);
    // Initialisation du nouveau formData
    var newFormData = new FormData();

    // regex pour verification des lignes modifiées => check si la ligne est un marqueur de modif
    var reg = /day\[[0-9]+\]\[[0-9]+\]\[[A-z][a-z]\]\[mod]/gm;

    var cpt = 0; // Compteur pour récuperer les 2 lignes apres le marqueur
    var cptModif = 0; // Compreur pour vérifier si modifications
    var lastpair = ""; // Dernère valeur de marqueur

    // Analyse du form data et valorisation du newFormData
    for (var pair of oldFormData.entries()) {
        // test si Ligne de temps ou non
        if (pair[0].startsWith("day") != true) {
            // Toute ligne ne contenant pas day est acceptée
            newFormData.append(pair[0], pair[1]);
            if (pair[0].startsWith("validat") == true && pair[1] == "1") {
                cptModif++;
            }
        } else {
            // Test ligne modifiée ?
            if ((m = reg.exec(pair[0])) !== null) {
                if (m.index === reg.lastIndex) {
                    reg.lastIndex++;
                }

                lastpair = pair[1];
                switch (lastpair) {
                    case "1":
                        if (lastpair == "1" && cpt < 3) {
                            cpt++;
                        }
                        break;
                    case "0":
                        if (cpt == 3) {
                            cpt = 0;
                        }
                        break;
                }
            } else {
                if (lastpair == "1"){
                    switch (cpt) {
                        case 1:
                            newFormData.append(pair[0], pair[1]);
                            cpt++;
                            break;
                        case 2:
                            newFormData.append(pair[0], pair[1]);
                            cpt++;
                            cptModif++;
                            break;
                        case 3:
                            newFormData.append(pair[0], pair[1]);
                            cpt = 1;
                            break;
                    }
                }
            }
        }
    }
    // Si modif on envoie la requête avec le nouveau formdata
    if (cptModif > 0) {
        // for (var newPair of newFormData.entries()) {
        //     console.log(newPair[0], newPair[1]);
        // }
        $('#btn_enregistrer').hide();
        $('#loader').show();
        var request = new XMLHttpRequest();
        request.open("POST", form.action);

        request.onreadystatechange = function() { //Appelle une fonction au changement d'état.
            if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                // console.log("Requête finie, traitement ici.");
                $('#loader').hide();
                $('#btn_enregistrer').show();
                document.location.reload(true);
            }
        }

        request.send(newFormData);
    } else {
        // console.log("pas de modif");
    }
}

function modifyUser(that) {
    var val = $(that).val();
    var idu = val;
    var tr = $(that).parent().parent();
    var selectClient = $(tr).find("td.cel_client").children();
    $(selectClient)
        .find("option")
        .each(function () {
            if (val == 0) {
                if ($(this).val() == val) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            } else {
                if ($.inArray($(this).val(), optionClients[idu]) != -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            }
        });
    if (
        $(selectClient).find('option[selected="selected"]').css("display") !=
            "none" &&
        $(selectClient).find('option[selected="selected"]').length
    ) {
        $(selectClient).val(
            $(selectClient).find('option[selected="selected"]').val()
        );
    } else {
        if (val == 0 || optionClients[idu] == null) {
            $(selectClient).val(0);
        } else {
            $(selectClient).val(optionClients[idu][0]);
        }
    }
    $(selectClient).change();

    if (first == false) {
        var arrayTr = $('tr[user="' + idu + '"]');
        var arrayId = [];
        var idLine;

        var max = -1;
        var new_val = -1;
        arrayTr.each(function () {
            new_val = $(this).attr("id");
            if ($.isNumeric(new_val)) {
                if (new_val > max) {
                    max = new_val;
                }
            }
        });
        idLine = max;
        arrayTr.each(function () {
            arrayId.push(Number($(this).attr("idLine")));
        });
        for (var i = 0; i <= arrayTr.length; i++) {
            if ($.inArray(i, arrayId) == -1) {
                idLine = i;
                break;
            }
        }
        $(tr).attr("idLine", idLine);
        $(tr).attr("user", idu);

        $(that).attr("name", "users[" + idu + "][" + idLine + "]");
        $(selectClient).attr("name", "client[" + idu + "][" + idLine + "]");
        var selectProjet = $(tr).find("td.cel_projet").children();
        $(selectProjet).attr("name", "projet[" + idu + "][" + idLine + "]");
        var selectProfil = $(tr).find("td.cel_profil").children();
        $(selectProfil).attr("name", "profil[" + idu + "][" + idLine + "]");
        var selectActivit = $(tr).find("td.cel_activit").children();
        $(selectActivit).attr(
            "name",
            "activities[" + idu + "][" + idLine + "]"
        );
        var tdSelectLast = $(tr).find("td.cel_detail");
        var inputDetail = $(tdSelectLast).children("div").children("input");
        $(inputDetail).attr("name", "detail[" + idu + "][" + idLine + "]");
        $(inputDetail).attr("idLine", "detail-" + idu + "-" + idLine);
        arrayDays.forEach(function (idDay) {
            tdSelectLast = $(tdSelectLast).next();
            var inputCurrentText = $(tdSelectLast)
                .children()
                .find('input[type="text"]');
            var inputCurrentHiddenTemp = $(tdSelectLast).children()[1];
            if (inputCurrentHiddenTemp == undefined) {
                inputCurrentHiddenTemp = $(tdSelectLast)
                    .children()
                    .children()[1];
            }
            if (first == false && add == false) {
                // Ajout de marqueur sur toute la ligne en cas de modif User
                var inputCurrentHiddenMod = $(tdSelectLast)
                    .children()
                    .children()[0];
                if (inputCurrentHiddenMod.className == "numericer") {
                    inputCurrentHiddenMod = $(tdSelectLast).children()[0];
                }
                inputCurrentHiddenMod.value = 1;
                $(inputCurrentHiddenMod).attr(
                    "name",
                    "day[" + idu + "][" + idLine + "][" + idDay + "][mod]"
                );
            }
            if (inputCurrentHiddenTemp.type === "hidden") {
                var inputCurrentHidden = inputCurrentHiddenTemp;
                $(inputCurrentHidden).attr(
                    "name",
                    "day[" + idu + "][" + idLine + "][" + idDay + "][id]"
                );
            }
            $(inputCurrentText).attr(
                "id",
                "day-" + idu + "-" + idLine + "-" + idDay
            );
            $(inputCurrentText).attr(
                "name",
                "day[" + idu + "][" + idLine + "][" + idDay + "][time]"
            );
        });
    }
}

function modifyClient(that) {
    var val = $(that).val();
    if (val != 0) {
        var idu = val.split(".")[0];
        var idc = val.split(".")[1];
    }
    var select = $(that).parent().parent().find("td.cel_projet").children();
    $(select)
        .find("option")
        .each(function () {
            if (val == 0) {
                if ($(this).val() == 0) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            } else {
                if (
                    $.inArray($(this).val(), optionProjects[idu + "." + idc]) !=
                    -1
                ) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            }
        });
    if (
        $(select).find('option[selected="selected"]').css("display") !=
            "none" &&
        $(select).find('option[selected="selected"]').length
    ) {
        $(select).val($(select).find('option[selected="selected"]').val());
    } else {
        if (val == 0) {
            $(select).val(val);
        } else {
            $(select).val(optionProjects[idu + "." + idc][0]);
        }
    }

    //création de marqueur lors de la modification du client
    marqueMod(that);

    $(select).change();
}

function modifyProject(that) {
    var val = $(that).val();
    var idp = val.split(".")[2];
    var select = $(that).parent().parent().find("td.cel_activit").children();
    $(select)
        .find("option")
        .each(function () {
            if (val == 0) {
                if ($(this).val() == 0) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            } else {
                if ($.inArray($(this).val(), optionActivits[idp]) != -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            }
        });
    if (
        $(select).find('option[selected="selected"]').css("display") !=
            "none" &&
        $(select).find('option[selected="selected"]').length
    ) {
        $(select).val($(select).find('option[selected="selected"]').val());
    } else {
        if (val == 0) {
            $(select).val(val);
        } else {
            $(select).val(optionActivits[idp][0]);
        }
    }
    var select2 = $(that).parent().parent().find("td.cel_profil").children();
    $(select2)
        .find("option")
        .each(function () {
            if (val == 0) {
                if ($(this).val() == 0) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            } else {
                if ($.inArray($(this).val(), optionProfils[idp]) != -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            }
        });
    if (
        $(select2).find('option[selected="selected"]').css("display") !=
            "none" &&
        $(select2).find('option[selected="selected"]').length
    ) {
        $(select2).val($(select2).find('option[selected="selected"]').val());
    } else {
        if (val == 0) {
            $(select2).val(val);
        } else {
            $(select2).val(optionProfils[idp][0]);
        }
    }

    //création de marqueur lors de la modification du projet
    marqueMod(that);

}

function marqueMod(that, delet = false){
    //création de marqueur lors de la modification du client
    if (first == false && add == false) {
        // debugger;
        var tr = $(that).parent().parent();
        var tdSelectLast = $(tr).find("td.cel_detail");
        arrayDays.forEach(function (idDay) {
            tdSelectLast = $(tdSelectLast).next();
            var inputCurrentHiddenMod = $(tdSelectLast)
                .children()
                .children()[0];
            if (inputCurrentHiddenMod.className == "numericer") {
                if (delet) {
                    inputCurrentHiddenMod.value = "";
                }
                inputCurrentHiddenMod = $(tdSelectLast).children()[0];
            }
            inputCurrentHiddenMod.value = 1;
        });
    }
    updateTotal();
}

//création de marqueur lors de la modification du profil
function modifyProfil(that) {
    marqueMod(that);
}


//création de marqueur lors de la modification de l'activité
function modifyActivite(that) {
    marqueMod(that);
}

function delLine(that) {
    var tr = $(that).parent().parent();
    marqueMod(that, true);
    $(tr).hide();
    // $(that).parent().parent().remove();
}

function addLine(that) {
    add = true;
    var id = 0;
    idUser = optionUsers[0];
    var tr = $("<tr>", {
        idLine: id,
        user: idUser,
    });
    var tdButton = $("<td>", {
        class: "action",
        scope: "col",
    });
    var button = $("<button>", {
        type: "button",
        class: "btn btn-danger remove",
        text: "-",
    });
    button.click(function () {
        delLine(this);
    });
    tdButton.append(button);
    tr.append(tdButton);
    // User
    var tdUser = $("<td>", {
        class: "cel_users",
        scope: "col",
    });
    var selectUser = $("<select>", {
        class: "users",
        name: "users[" + idUser + "][" + id + "]",
    });
    for (var key in valueUsers) {
        var option = $("<option>", {
            value: key,
            text: valueUsers[key],
        });
        selectUser.append(option);
    }
    selectUser.change(function () {
        modifyUser(this);
    });
    tdUser.append(selectUser);
    tr.append(tdUser);
    // Client
    var tdClient = $("<td>", {
        class: "cel_client",
        scope: "col",
    });
    var selectClient = $("<select>", {
        class: "client",
        name: "client[" + idUser + "][" + id + "]",
    });
    for (var key in valueClients) {
        var option = $("<option>", {
            value: key,
            text: valueClients[key],
        });
        selectClient.append(option);
    }
    selectClient.change(function () {
        modifyClient(this);
    });
    tdClient.append(selectClient);
    tr.append(tdClient);
    // Projet
    var tdProjet = $("<td>", {
        class: "cel_projet",
        scope: "col",
    });
    var selectProjet = $("<select>", {
        class: "project",
        name: "projet[" + idUser + "][" + id + "]",
    });
    for (var key in valueProjects) {
        var option = $("<option>", {
            value: key,
            text: valueProjects[key],
        });
        selectProjet.append(option);
    }
    selectProjet.change(function () {
        modifyProject(this);
    });
    tdProjet.append(selectProjet);
    tr.append(tdProjet);
    // Profil
    var tdProfil = $("<td>", {
        class: "cel_profil",
        scope: "col",
    });
    var selectProfil = $("<select>", {
        class: "profil",
        name: "profil[" + idUser + "][" + id + "]",
    });
    for (var key in valueProfils) {
        var option = $("<option>", {
            value: key,
            text: valueProfils[key],
        });
        selectProfil.append(option);
    }
    tdProfil.append(selectProfil);
    tr.append(tdProfil);

    selectProfil.change(function () {
        modifyProfil(this);
    });
    // Activité
    var tdActivit = $("<td>", {
        class: "cel_activit",
        scope: "col",
    });
    var selectActivit = $("<select>", {
        class: "activit",
        name: "activities[" + idUser + "][" + id + "]",
    });
    for (var key in valueActivits) {
        var option = $("<option>", {
            value: key,
            text: valueActivits[key],
        });
        selectActivit.append(option);
    }
    tdActivit.append(selectActivit);
    tr.append(tdActivit);
    selectActivit.change(function () {
        modifyActivite(this);
    });
    //Detail
    var tdDetail = $("<td>", {
        class: "cel_detail",
        scope: "col",
    });
    var divDetail = $("<div>", {
        class: "input text",
    });
    var inputDetail = $("<input>", {
        type: "text",
        name: "detail[" + idUser + "][" + id + "]",
        id: "detail-" + idUser + "-" + id,
    });
    divDetail.append(inputDetail);
    tdDetail.append(divDetail);
    tr.append(tdDetail);
    // Days
    arrayDays.forEach(function (idDay) {
        var tdDay = $("<td>", { scope: "col" });
        var divDay = $("<div>", { class: "input text" });
        var hiddenDayMod = $("<input>", {
            name: "day[" + idUser + "][" + id + "][" + idDay + "][mod]",
            type: "hidden",
            value: 0,
        });
        var hiddenDay = $("<input>", {
            name: "day[" + idUser + "][" + id + "][" + idDay + "][id]",
            type: "hidden",
        });
        var inputDay = $("<input>", {
            id: "day-" + idUser + "-" + id + "-" + idDay,
            name: "day[" + idUser + "][" + id + "][" + idDay + "][time]",
            type: "text",
            class:"numericer",
        });
        inputDay.on("input", function () {
            numericer(this);
            updateTotal();
        });

        divDay.append(inputDay);
        tdDay.append(hiddenDayMod);
        tdDay.append(hiddenDay);
        tdDay.append(divDay);
        tr.append(tdDay);
    });

    tr.insertBefore("#total");

    selectUser.change();
    add = false;
}


function numericer(that) {
    var regex = /^([0-9])+([.])?([0-9]+)?/g;
    var arrayString = $(that).val().match(regex);
    // console.log(arrayString);
    if (arrayString !== null) {
        $(that).val(arrayString.join(""));
    }

    //création de marqueur lors de la modification des temps
    if (first == false && add == false) {
        var tr = $(that).parent().parent();
        var inputCurrentHiddenMod = $(that).parent().parent().children()[0];
        inputCurrentHiddenMod.value = 1;
    }
}

function updateTotal() {
    var nb = 8;
    arrayDays.forEach(function (idDay) {
        var arrayColLu = $(
            "#semainier > tbody > tr > td:nth-child(" + nb + ")"
        );
        var totalLu = 0;
        for (var i = 0; i < arrayColLu.length - 1; i++) {
            var value = $(arrayColLu[i])
                .children()
                .find("input[type=text]")
                .val();
            if (value == "") {
                value = 0;
            }
            totalLu += parseFloat(value);
        }
        var identifier = "#t" + idDay;
        $(identifier).text(Math.round(totalLu * 100) / 100);
        nb += 1;
    });
}
