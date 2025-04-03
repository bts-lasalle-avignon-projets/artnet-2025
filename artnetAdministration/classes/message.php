<?php
class Message
{
	public static function afficher($message, $type)
	{
		if (!empty($type)) {
			if ($type == 'erreur') {
				echo '<div class="card col mb-3 mx-3 bg-danger text-white"><div class="card-body">' . $message . '</div></div><div class="w-100"></div>';
			} else if ($type == 'info') {
				echo '<div class="card col mb-3 mx-3 bg-info text-white"><div class="card-body">' . $message . '</div></div><div class="w-100"></div>';
			} else if ($type == 'debug') {
				echo '<div class="card col mb-3 mx-3 bg-warning text-white"><div class="card-body">' . $message . '</div></div><div class="w-100"></div>';
			}
		} else {
			echo '<div class="card col mb-3 mx-3 bg-success text-white"><div class="card-body">' . $message . '</div></div><div class="w-100"></div>';
		}
	}
}
