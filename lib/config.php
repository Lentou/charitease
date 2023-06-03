<?php

// CharitEase Version
const NAME = 'CharitEase';
const VERSION = 1.2;
const DATABASE_NAME = 'CharitEase';

/* DATABASE TABLES (11)
User Account Table: 1
- user_id (primary key)
- email (unique)
- password (encrypted)
- account_type (donor or charity)
- is_verified (boolean, default to false)
- verification_pin (varchar, unique, nullable example = 'J7HkP2e9Ls')

Donors Table: 2
- donor_id (primary key, foreign key to User Account Table)
- donor_name
- donor_contact_name
- donor_address
- donor_type (individual or organization)
- org_type (if organization)
- donor_phone
- date_approved
- is_approved (boolean)

Charity Organizations Table: 3
- org_id (primary key, foreign key to User Account Table)
- org_name
- org_person_name
- org_phone
- org_address
- is_approved (boolean)
- org_description (can be null)
- org_type (type of organization)
- date_founded
- date_approved

Admins Table: 4
- admin_id (primary key)
- admin_name
- admin_email
- admin_password (encrypted)

Conversations Table: 5
- convo_id (primary key)
- donor_id (foreign key to Donors Table)
- org_id (foreign key to Charity Organizations Table)
- initiated_by (donor or charity)
- message varchar(4096)
- timestamp
- is_read

Donor Ratings Table: 6
- rating_id (primary key)
- donor_id (foreign key to Donors Table)
- org_id (foreign key to Charity Organizations Table)
- rating
- review
- timestamp

Donations Table: 7
- donation_id (primary key)
- donor_id (foreign key to User Accounts Table)
- org_id (foreign key to Charity Organizations Table)
- event_id (foreign key to Timeline Table)
- donation_type (values of "inkind" or "monetary")
- donation_amount (decimal) (quantity of item or money)
- donation_name (for inkind)
- donation_description (for inkind text)
- donation_category
- donation_date (datetime) (delivery date or date of sended the money)
- status (values of "pending", "approved", or "rejected") for donors

Charity Organization Timeline Events Table: 8
- event_id (primary key)
- org_id (foreign key to Charity Organizations Table)
- event_title
- event_type (announcement or event)
- event_description text
- event_start_date
- event_end_date
- current_inkind
- target_inkind
- current_funds
- target_funds
- timestamp
- status

Charity Organization Queue Timeline Events Table: 9
- queue_id (primary key)
- event_id (foreign key to Timeline CAN BE NULL)
- org_id (foreign key to Charity Organizations Table)
- queue_title
- queue_type (announcement or event)
- queue_description text
- queue_start_date (can be null)
- queue_end_date (can be null)
- current_inkind (can be null)
- target_inkind (can be null)
- current_funds (can be null)
- target_funds (can be null)
- timestamp
- queue_status

Image Table: 10
- image_id (primary key)
- table_id (foreign key to the relevant table)
- permit_type (varchar) = (permit, valid ids, blog, event)
- category (e.g., donor_permit, org_permit, event_image, edit_image, donation_image)
- image_name (varchar) <-- Column to store the image file name
- image_type (varchar) <-- New column to store the image type or format (eg., jpeg, png, jpg)
- image_data (BLOB)

Payment Table: 11
- payment_id (primary key)
- event_id (foreign key to Timeline CAN BE NULL)
- org_id (foreign key to Organization Table)
- method_type (GCash, Paypal, Maya)
- account_details (Details of Account by TEXT json_encpde)

Need Images (
    Queue Timeline (IMAGES), 
    Timeline (IMAGES), 
    Donations (INKIND), 
    Organization (ICON, PERMIT), 
    Donors (VALID IDS, PERMIT, ICON)
)





*/

?>