<?php
declare(strict_types=1);

namespace Swaggier\Communication\Foundation;

enum HttpMethods
{
	case GET;
	case POST;
	case PUT;
	case PATCH;
	case DELETE;

}
