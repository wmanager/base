<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * Name: Auth Lang - English
 *
 * Author: Ben Edmunds
 * ben.edmunds@gmail.com
 * @benedmunds
 *
 * Author: Benoit LIETAER
 * @gmail.com
 *
 * Location: http://github.com/benedmunds/ion_auth/
 *
 * Created: 14.02.2014
 *
 * Description: French language file for Ion Auth example views
 */
	
// Errors
$lang ['error_csrf'] = 'La validation de ce formulaire a échoué.';

// Login
$lang ['login_heading'] = 'Se connecter';
$lang ['login_subheading'] = "Veuillez vous connecter avec votre nom d'utilisateur et votre mot de passe.";
$lang ['login_identity_label'] = "E-mail/Nom d'utilisateur:";
$lang ['login_password_label'] = 'Mot de passe:';
$lang ['login_remember_label'] = 'Rester connecté:';
$lang ['login_submit_btn'] = 'Se connecter';
$lang ['login_forgot_password'] = 'Mot de passe oublié ?';

// Index
$lang ['index_heading'] = 'Utilisateurs';
$lang ['index_subheading'] = 'Ci dessous se trouve la liste des utilisateurs.';
$lang ['index_fname_th'] = 'Prénom';
$lang ['index_lname_th'] = 'Nome de famille';
$lang ['index_email_th'] = 'E-mail';
$lang ['index_groups_th'] = 'Groupes';
$lang ['index_status_th'] = 'Status';
$lang ['index_action_th'] = 'Action';
$lang ['index_active_link'] = 'Activé';
$lang ['index_inactive_link'] = 'Inactive';
$lang ['index_create_user_link'] = 'Créer un nouvel utilisateur';
$lang ['index_create_group_link'] = 'Créer un nouveau groupe';

// Deactivate User
$lang ['deactivate_heading'] = 'Désactiver un utilisateur';
$lang ['deactivate_subheading'] = "Êtes-vous certain de vouloir désactiver l'utilisateur \'%s\'";
$lang ['deactivate_confirm_y_label'] = 'Oui:';
$lang ['deactivate_confirm_n_label'] = 'Non:';
$lang ['deactivate_submit_btn'] = 'Envoyer';
$lang ['deactivate_validation_confirm_label'] = 'confirmation';
$lang ['deactivate_validation_user_id_label'] = 'Identifiant';

// Create User
$lang ['create_user_heading'] = 'Créer un utilisateur';
$lang ['create_user_subheading'] = 'Veuillez entrer les informations ci-dessous.';
$lang ['create_user_fname_label'] = 'Prénom:';
$lang ['create_user_lname_label'] = 'Nom de famille:';
$lang ['create_user_company_label'] = 'Société:';
$lang ['create_user_email_label'] = 'E-mail:';
$lang ['create_user_phone_label'] = 'Téléphone:';
$lang ['create_user_password_label'] = 'Mot de passe:';
$lang ['create_user_password_confirm_label'] = 'Confirmer le Mot de passe:';
$lang ['create_user_submit_btn'] = 'Créer l\'utilisateur';
$lang ['create_user_validation_fname_label'] = 'Prénom';
$lang ['create_user_validation_lname_label'] = 'Nom de famille';
$lang ['create_user_validation_email_label'] = 'Adresse E-mail';
$lang ['create_user_validation_phone1_label'] = 'Première partie du numéro de téléphone';
$lang ['create_user_validation_phone2_label'] = 'Seconde partie du numéro de téléphone';
$lang ['create_user_validation_phone3_label'] = 'Troisième partie du numéro de téléphone';
$lang ['create_user_validation_company_label'] = 'Société';
$lang ['create_user_validation_password_label'] = 'Mot de passe';
$lang ['create_user_validation_password_confirm_label'] = 'Confirmation du mot de passe';

// Edit User
$lang ['edit_user_heading'] = 'Editer l\'utilisateur';
$lang ['edit_user_subheading'] = 'Veuillez entrer les données de l\'utilisateur ci-dessous.';
$lang ['edit_user_fname_label'] = 'Prénom:';
$lang ['edit_user_lname_label'] = 'Nom de famille:';
$lang ['edit_user_company_label'] = 'Société:';
$lang ['edit_user_email_label'] = 'E-mail:';
$lang ['edit_user_phone_label'] = 'Téléphone:';
$lang ['edit_user_password_label'] = 'Mot de passe: (si modifié)';
$lang ['edit_user_password_confirm_label'] = 'Confirmer le mot de passe:';
$lang ['edit_user_groups_heading'] = 'Membre du groupe';
$lang ['edit_user_submit_btn'] = 'Enregistrer l\'utilisateur';
$lang ['edit_user_validation_fname_label'] = 'Prénom';
$lang ['edit_user_validation_lname_label'] = 'Nom de famille';
$lang ['edit_user_validation_email_label'] = 'Adresse email';
$lang ['edit_user_validation_phone1_label'] = 'Première partie du numéro de téléphone';
$lang ['edit_user_validation_phone2_label'] = 'Seconde partie du numéro de téléphone';
$lang ['edit_user_validation_phone3_label'] = 'Troisième partie du numéro de téléphone';
$lang ['edit_user_validation_company_label'] = 'Société';
$lang ['edit_user_validation_groups_label'] = 'Groupes';
$lang ['edit_user_validation_password_label'] = 'Mot de passe';
$lang ['edit_user_validation_password_confirm_label'] = 'Confirmation du Mot de passe';

// Create Group
$lang ['create_group_title'] = 'Créer le Groupe';
$lang ['create_group_heading'] = 'Créer le Groupe';
$lang ['create_group_subheading'] = 'Veuillez entrer les information du groupe ci-dessous.';
$lang ['create_group_name_label'] = 'Nom du groupe:';
$lang ['create_group_desc_label'] = 'Description:';
$lang ['create_group_submit_btn'] = 'Créer le Groupe';
$lang ['create_group_validation_name_label'] = 'Nom du Groupe';
$lang ['create_group_validation_desc_label'] = 'Description';

// Edit Group
$lang ['edit_group_title'] = 'Editer le Groupe';
$lang ['edit_group_saved'] = 'Groupe enregistré';
$lang ['edit_group_heading'] = 'Editer le  Groupe';
$lang ['edit_group_subheading'] = 'Veuillez entrer les information du groupe ci-dessous.';
$lang ['edit_group_name_label'] = 'Nom du Groupe:';
$lang ['edit_group_desc_label'] = 'Description:';
$lang ['edit_group_submit_btn'] = 'Enregisterr le Groupe';
$lang ['edit_group_validation_name_label'] = 'nom du Groupe';
$lang ['edit_group_validation_desc_label'] = 'Description';

// Change Password
$lang ['change_password_heading'] = 'Changer le mot de passe';
$lang ['change_password_old_password_label'] = 'Ancien mot de passe:';
$lang ['change_password_new_password_label'] = 'New Password (doit contenir %s caractères minimum):';
$lang ['change_password_new_password_confirm_label'] = 'Confirmer le nouveau mot de passe:';
$lang ['change_password_submit_btn'] = 'Enregistrer';
$lang ['change_password_validation_old_password_label'] = 'Ancien mot de passe';
$lang ['change_password_validation_new_password_label'] = 'Nouveau mot de passe';
$lang ['change_password_validation_new_password_confirm_label'] = 'Confirmer le nouveau mot de passe';

// Forgot Password
$lang ['forgot_password_heading'] = 'Mot de passe oublié';
$lang ['forgot_password_subheading'] = 'Veuillez entrer votre %s pour que nous puissions vous envoyer votre nouveau mot de passe.';
$lang ['forgot_password_email_label'] = '%s:';
$lang ['forgot_password_submit_btn'] = 'Envoyer';
$lang ['forgot_password_validation_email_label'] = 'Adresse E-mail';
$lang ['forgot_password_username_identity_label'] = 'Nom d\'utilisateur';
$lang ['forgot_password_email_identity_label'] = 'E-mail';
$lang ['forgot_password_email_not_found'] = 'Cette adresse email n\'est pas enregistrée chez nous.';

// Reset Password
$lang ['reset_password_heading'] = 'Modifier le mot de passe';
$lang ['reset_password_new_password_label'] = 'Nouveau mot de passe (au minimum %s caractères):';
$lang ['reset_password_new_password_confirm_label'] = 'Confirmez le nouveau mot de passe:';
$lang ['reset_password_submit_btn'] = 'Enregistrer';
$lang ['reset_password_validation_new_password_label'] = 'Nouveau mot de passe';
$lang ['reset_password_validation_new_password_confirm_label'] = 'Confirmer le nouveau mot de passe';

// Activation Email
$lang ['email_activate_heading'] = 'Activer le compte pour %s';
$lang ['email_activate_subheading'] = 'Veuillez cliquer sur le lien pour %s.';
$lang ['email_activate_link'] = 'Activer votre compte';

// Forgot Password Email
$lang ['email_forgot_password_heading'] = 'Changer le mot de passe pour %s';
$lang ['email_forgot_password_subheading'] = 'Veuillez cliquer sur ce lien pour %s.';
$lang ['email_forgot_password_link'] = 'Changer votre mot de passe';

// New Password Email
$lang ['email_new_password_heading'] = 'Nouveau mot de passe pour %s';
$lang ['email_new_password_subheading'] = 'Votre mot de passe a été changé pour: %s';

