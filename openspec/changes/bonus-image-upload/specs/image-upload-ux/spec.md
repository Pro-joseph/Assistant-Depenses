## ADDED Requirements

### Requirement: Image preview before upload
The system SHALL display a preview of the selected image immediately after the user selects/chooses a file, without requiring a form submission.

#### Scenario: User selects an image file
- **WHEN** the user picks a file via the file input on create or edit forms
- **THEN** a preview of the image appears in the upload zone
- **AND** the dashed-border upload icon is replaced by the preview
- **AND** the file name and size are displayed below the preview

#### Scenario: User removes the selection
- **WHEN** the user clicks a "Supprimer la sélection" button
- **THEN** the preview disappears
- **AND** the file input is cleared
- **AND** the default upload zone reappears

### Requirement: Drag-and-drop upload
The upload zone on create and edit forms SHALL support drag-and-drop file selection.

#### Scenario: User drags a file over the zone
- **WHEN** the user drags a file over the upload zone
- **THEN** the zone border changes to a highlighted state (e.g., primary color, background tint)

#### Scenario: User drops a file on the zone
- **WHEN** the user drops an image file on the upload zone
- **THEN** the file is accepted as if selected via the file input
- **AND** the preview is shown

#### Scenario: User drops a non-image file
- **WHEN** the user drops a non-image file on the upload zone
- **THEN** a brief error message is shown (e.g., "Format non supporté")
- **AND** nothing changes in the upload zone
