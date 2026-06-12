## ADDED Requirements

### Requirement: Recu index shows a thumbnail when image exists
The system SHALL display a visual indicator (thumbnail or icon) in the recus list to show whether a recu has an attached image.

#### Scenario: Recu has an image
- **WHEN** a recu with `image_path` is displayed in the list
- **THEN** a small rounded thumbnail of the image is shown
- **AND** clicking the thumbnail opens the full image (or the recu detail page)

#### Scenario: Recu does not have an image
- **WHEN** a recu without `image_path` is displayed in the list
- **THEN** a muted document icon is shown instead of a thumbnail

#### Scenario: Thumbnail is properly sized
- **WHEN** the thumbnail is rendered
- **THEN** it has a fixed size (40x40 pixels) to maintain table row consistency
