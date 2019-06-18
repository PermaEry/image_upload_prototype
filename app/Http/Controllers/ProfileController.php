<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\UploadsFiles;

class ProfileController extends Controller
{

	use UploadsFiles;


	/**
	 * ProfileController constructor. Registers authentication middleware for the entire controller.
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Returns the profile index view.
	 *
	 * @return view
	 */
	public function index()
	{
		return view('auth.profile');
	}

	/**
	 *
	 *
	 *
	 */
	public function updateProfile(Request $request)
	{
		// Form validation
		$request->validate([

			'name'				=> 'required', 

			'profile_image' 	=> 'required|image|mimes:jpeg,png,jpg,gif|max:2048'

		]);

		// Currently authenticated user
		$user = auth()->user();

		// Update user name
		$user->name = $request->input('name');

		if ($request->has('profile_image'))
		{

			$image = $request->file('profile_image');

			// Make a image name from the user name and timestamp
			$name = str_slug($request->input('name') . '_' . time());

			$folder = '/uploads/images/';

			// Full path to where the image will be stored
			$filePath = $folder . $name . '.' . $image->getClientOriginalExtension();

			// Store the image
			$this->uploadFile($image, $folder, 'public', $name);

			if ($user->profile_image)
				$this->deleteFile($folder, 'public', $user->profile_image);

			// Update user profile image path to the new file path
			$user->profile_image = $filePath;

		}

		// Save user updated information in the database
		$user->save();

		// Redirect to profile index, with status message.
		return redirect()->back()->with(['status' => 'Profile updated Successfully.']);

	}
}
