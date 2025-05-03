
<img src="https://github.com/user-attachments/assets/a3cc61b1-6753-4e68-b2cc-64c8c97e5ecd">

# qdl-webgui
QDL-WebGUI is an experimental browser-based client for <a href="https://github.com/vitiko98/qobuz-dl">qobuz-dl</a>. It is written in PHP and currently supports linux-based systems only.

## Features
- Simple, compact UI. Written in PHP and uses minimal amount of Javascript.
- Easy configuration. Adjust your qobuz settings using a friendly setup wizard.
- Many search options. You can search for artists, albums, and labels.


## Requirements
- PHP 8.2
- An installation of qobuz-dl that's done with **pipx**.
  (**04/23/25**: I've added support for non-pipx users, however, I'll need somebody to test it out.)

- A Qobuz account with an active streaming subscription.
- simplehtmldom installed to the projects directory in the following path: qdlwebgui/lib/simplehtmldom/
  
  (**NEW**: The frontend can now install SimpleHTMLDOM for you)


## To-do list
1. Label pages - Done (Basic functionality)
2. Artist pages - Done (Basic functionality)
3. Settings page to edit config.ini - Done. Soon to also add advanced options/flags.
4. Convert links for featured album/discography banners to work with this frontend - Partially Done

## Screenshots
### Home screen
<img src="https://github.com/user-attachments/assets/749264b8-b6c0-4ee2-a7b9-c8cf1a82e0c0">

### Search results page
<img src="https://github.com/user-attachments/assets/406ca700-b752-4241-9d50-0388bda9b104">

### Album page
<img src="https://github.com/user-attachments/assets/9e93c877-7ef3-41ce-b65a-c22a54b1cc01">

### Download progress
<img src="https://github.com/user-attachments/assets/81b40edb-849b-4fa2-9278-4ca141311766">

### Setup wizard - Step Two
<img src="https://github.com/user-attachments/assets/f7a9cf90-cf8e-4025-a1cc-9f840af93694">

### Album download result
<img src="https://github.com/user-attachments/assets/b9c6ac8c-d0bd-4139-80cc-2ab82772a577">


