var skeletonId = 'skeleton';
var contentId = 'content';
var skipCounter = 0;
var takeAmount = 10;

//------------- Get All tab data on page load -------------
function getAllTabsData() {
    var params = '?page=1&get_suggestions=1&get_sent_requests=1&get_received_requests=1&get_connection=1';
    ajax('/connection/userconnection' + params, 'GET', ['UpdateSuggestionsTabData', 'UpdateSentReqTabData', 'UpdateReceivedReqTabData', 'UpdateConnectionTabData']);
}

//------------- Get Sent Request data -------------
function getRequests() {
    $('#sent_request_table tbody').html("");
    var params = '?page=1&get_sent_requests=1';
    ajax('/connection/userconnection' + params, 'GET', ['UpdateSentReqTabData']);
}

//------------- Get Received Request data -------------
function getReceivedRequests() {
    $('#received_request_table tbody').html("");
    var params = '?page=1&get_received_requests=1';
    ajax('/connection/userconnection' + params, 'GET', ['UpdateReceivedReqTabData']);
}

function getMoreRequests(mode) {
    // Optional: Depends on how you handle the "Load more"-Functionality
    // your code here...
}

//------------- Get connection data -------------
function getConnections() {
    $('#connection_table tbody').html("");
    var params = '?page=1&get_connection=1';
    ajax('/connection/userconnection' + params, 'GET', ['UpdateConnectionTabData']);
}

function getMoreConnections() {
  // Optional: Depends on how you handle the "Load more"-Functionality
  // your code here...
}

function getConnectionsInCommon(userId, connectionId) {
  // your code here...
}

function getMoreConnectionsInCommon(userId, connectionId) {
  // Optional: Depends on how you handle the "Load more"-Functionality
  // your code here...
}

function getSuggestions() {
    $('#suggistion_table tbody').html("");
    var params = '?page=1&get_suggestions=1';
    ajax('/connection/userconnection' + params, 'GET', ['UpdateSuggestionsTabData']);
}

//------------------ Update data of suggestion tab ------------------
function UpdateSuggestionsTabData(suggestionData) {
    var suggestionTable = '';
    $.each(suggestionData.data, function (key, value) {
        suggestionTable +=
            '<tr><td class="align-middle td_border"> <span>' + value.name + '</span> - <span>' + value.email + '</span></td>' +
            '<td class="text-end td_border"><button id="create_request_btn_" class="btn btn-primary me-1"> Connect</button></td></tr>';
    });
    //$('#suggistion_table').html(suggestionTable);
    $("#suggistion_table tbody").append(suggestionTable);
    //----------- total connection-----------
    var totalSuggestions = suggestionData.total;
    $('#suggesion_count').html(totalSuggestions);
//----------- Shown upto connection-----------
    var shownUptoSuggestions = suggestionData.to;
    var currentPage = suggestionData.current_page;
    if (shownUptoSuggestions < totalSuggestions) {
        var page = currentPage + 1;
        var params = "'" + page + '&get_suggestions=1' + "'";
        var suggestionTable = '<button class="btn btn-sm btn-primary" id="load_more_suggestions" onclick="showMoreData(' + "'UpdateSuggestionsTabData'," + params + ')">Load more </button>';
        $('#more_suggestion').html(suggestionTable);
    } else {
        $('#more_suggestion').html('');
    }
}

//------------------ Update data of sent request tab ------------------
function UpdateSentReqTabData(sentReqData) {
    var sentRequestTable = '';
    $.each(sentReqData.data, function (key, value) {
        sentRequestTable +=
            '<tr><td class="align-middle td_border"> <span>' + value.name + '</span> - <span>' + value.email + '</span></td>' +
            '<td class="text-end td_border"><button id="cancel_request_btn_" class="btn btn-danger me-1"> Withdraw Request</button></td></tr>';
    });
    //$('#sent_request_table').html(sentRequestTable);
    $("#sent_request_table tbody").append(sentRequestTable);
    //----------- total connection-----------
    var totalSentRequest = sentReqData.total;
    $('#sent_request_count').html(totalSentRequest);
    //----------- Shown upto connection-----------
    var shownUptoSentRequests = sentReqData.to;
    var currentPage = sentReqData.current_page;
    if (shownUptoSentRequests < totalSentRequest) {
        var page = currentPage + 1;
        var params = "'" + page + '&get_sent_requests=1' + "'";
        var sentRequestTable = '<button class="btn btn-sm btn-primary" id="load_more_requests" onclick="showMoreData(' + "'UpdateSentReqTabData'," + params + ')">Load more </button>';
        $('#more_request').html(sentRequestTable);
    } else {
        $('#more_request').html('');
    }
}

//------------------ Update data of received request tab ------------------
function UpdateReceivedReqTabData(receivedReqData) {
    var receivedRequestTable = '';
    $.each(receivedReqData.data, function (key, value) {
        receivedRequestTable +=
            '<tr><td class="align-middle td_border"> <span>' + value.name + '</span> - <span>' + value.email + '</span></td>' +
            '<td class="text-end td_border"><button id="accept_request_btn_" class="btn btn-danger me-1"> Accept</button></td></tr>';
    });
    //$('#received_request_table').html(receivedRequestTable);
    $("#received_request_table tbody").append(receivedRequestTable);
    //----------- total received Request -----------
    var totalReceivedRequest = receivedReqData.total;
    $('#received_request_count').html(totalReceivedRequest);
    //----------- Shown received Request -----------
    var shownUptoReceivedRequests = receivedReqData.to;
    var currentPage = receivedReqData.current_page;
    if (shownUptoReceivedRequests < totalReceivedRequest) {
        var page = currentPage + 1;
        var params = "'" + page + '&get_received_requests=1' + "'";
        var receivedRequestTable = '<button class="btn btn-sm btn-primary" id="load_more_received_requests" onclick="showMoreData(' + "'UpdateReceivedReqTabData'," + params + ')">Load more </button>';
        $('#more_received_request').html(receivedRequestTable);
    } else {
        $('#more_received_request').html('');
    }
}

//------------------ Update data of connection tab ------------------
function UpdateConnectionTabData(connectedData) {
    var connectionTable = '';
    $.each(connectedData.data, function (key, value) {
        connectionTable +=
            '<tr><td class="align-middle td_border"> <span>' + value.name + '</span> - <span>' + value.email + '</span></td>' +
            '<td class="text-end td_border"><button style="width: 220px" id="get_connections_in_common_" class="btn btn-primary" type="button" ' +
            'data-bs-toggle="collapse" data-bs-target="#collapse_" aria-expanded="false" ' +
            'aria-controls="collapseExample">Connections in common (' + value.commonconnections + ')</button>' +
            '<button id="create_request_btn_" class="btn btn-danger me-1">Remove Connection</button>' +
            '</td></tr>';
    });
    //$('#connection_table').html(connectionTable);
    $("#connection_table tbody").append(connectionTable);
    //----------- total connection-----------
    var totalConnected = connectedData.total;
    $('#connection_count').html(totalConnected);
//----------- Shown upto connection-----------
    var shownUptoConnected = connectedData.to;
    var currentPage = connectedData.current_page;
    if (shownUptoConnected < totalConnected) {
        var page = currentPage + 1;
        var params = "'" + page + '&get_connection=1' + "'";
        var connectionTable = '<button class="btn btn-sm btn-primary" id="load_more_connections" onclick="showMoreData(' + "'UpdateConnectionTabData'," + params + ')">Load more </button>';
        $('#more_connection').html(connectionTable);
    } else {
        $('#more_connection').html('');
    }
}

//------------------- Show more action -------------------
function showMoreData(actionType, page) {
    ajax('/connection/userconnection?page=' + page, 'GET', [actionType]);
}

function handleData(data, functionsOnSuccess) {
    //---------- UpdateSuggestionsTabData ----------
    let isUpdateSuggestionsTabData = functionsOnSuccess.includes("UpdateSuggestionsTabData")
    if (isUpdateSuggestionsTabData == true) {
        UpdateSuggestionsTabData(data.userSuggestionList);
    }
    //---------- UpdateSentReqTabData ----------
    let isUpdateSentReqTabData = functionsOnSuccess.includes("UpdateSentReqTabData")
    if (isUpdateSentReqTabData == true) {
        UpdateSentReqTabData(data.userSentRequestList);
    }
    //---------- UpdateReceivedReqTabData ----------
    let isUpdateReceivedReqTabData = functionsOnSuccess.includes("UpdateReceivedReqTabData")
    if (isUpdateReceivedReqTabData == true) {
        UpdateReceivedReqTabData(data.userReceivedRequestList);
    }
    //---------- UpdateConnectionTab
    // Data ----------
    let isUpdateConnection = functionsOnSuccess.includes("UpdateConnectionTabData")
    if (isUpdateConnection == true) {
        UpdateConnectionTabData(data.userConnectionList);
    }
}

function getMoreSuggestions() {
  // Optional: Depends on how you handle the "Load more"-Functionality
  // your code here...
}

function sendRequest(userId, suggestionId) {
  // your code here...
}

function deleteRequest(userId, requestId) {
  // your code here...
}

function acceptRequest(userId, requestId) {
  // your code here...
}

function removeConnection(userId, connectionId) {
  // your code here...
}

$(function () {
  //getSuggestions();
});

//----------- onclick button show specific component -----------
var tabs = document.connection_tabs.btnradio;
var prev = null;

$('#request_com,#request_received_com,#connection_com').css("display", "none");
for (var i = 0; i < tabs.length; i++) {
    tabs[i].onclick = function () {
        (prev) ? console.log(prev.id) : null;
        if (this !== prev) {
            prev = this;
            var selectedComponent = prev.id;
            if (selectedComponent == 'suggestions_div') {
                $("#suggestion_com").slideDown().siblings(".compenent_container").slideUp();
                getSuggestions();
            } else if (selectedComponent == 'request_sent_div') {
                $("#request_com").slideDown().siblings(".compenent_container").slideUp();
                getRequests();
            } else if (selectedComponent == 'request_received_div') {
                $("#request_received_com").slideDown().siblings(".compenent_container").slideUp();
                getReceivedRequests();
            } else if (selectedComponent == 'connection_div') {
                $("#connection_com").slideDown().siblings(".compenent_container").slideUp();
                getConnections();
            }

        }
    };
}

//------------------ First time load all tabs data ---------
getAllTabsData();
