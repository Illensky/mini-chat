    <?php
if (UserController::isUserConnected()) {
?>
<div id="chatDiv"></div>
<input type="text" id="message" name="message" placeholder="Tapez votre message ici.">
<button id="send-message">Envoyer</button>
    <?php
}
?>