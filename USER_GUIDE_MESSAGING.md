# Messaging System User Guide

## Overview
This guide explains how to use the enhanced messaging system that allows communication between Super Admins and regular users with full chat history and message management capabilities.

## For Regular Users

### Accessing Messages
1. Log in to the system
2. Navigate to the "Messages" section from the main menu
3. You'll see the messaging interface with a conversation sidebar

### Starting a New Conversation
1. In the message composition area, select a recipient from the dropdown menu
2. Type your message in the text area
3. Click "Send Message"
4. The conversation will appear in the chat history area

### Continuing an Existing Conversation
1. Click on any user in the conversation sidebar
2. The chat history with that user will load automatically
3. Type your message and click "Send Message"

### Deleting Messages
1. Find the message you want to delete
2. Click the "Delete" button (trash icon) next to the message
3. Confirm the deletion when prompted
4. The message will be removed from your view

Note: You can only delete messages that you sent or received. You cannot delete messages sent by others.

## For Super Admins

### Accessing the Admin Panel
1. Log in with Super Admin credentials
2. Navigate to `/admin` or click "Admin Panel" in the user menu
3. Select "Messages" from the Communication section

### Managing Messages
In the admin panel, you can:
- View all messages in the system
- Filter messages by sender or recipient
- Create new messages on behalf of users
- Edit existing messages
- Delete any message in the system
- Perform bulk operations on multiple messages

### Viewing Conversations
1. In the Messages list, you can see all conversations
2. Use the filters to narrow down to specific users
3. Click "View" to see detailed information about a message
4. Click "Edit" to modify a message

## Features

### Real-time Updates
- Messages appear instantly as they are sent
- No need to refresh the page to see new messages

### Conversation History
- All messages between two users are grouped together
- Easy to follow the flow of conversation
- Timestamps show when each message was sent

### Role Identification
- Super Admin users are clearly marked with a "Super Admin" badge
- Regular users have no special badge

### Responsive Design
- Works on desktop, tablet, and mobile devices
- Adapts to different screen sizes

### Security
- Messages can only be deleted by the sender or recipient
- All actions are logged for audit purposes
- Data is protected with industry-standard security practices

## Technical Information

### Message Structure
Each message contains:
- Sender (from_user_id)
- Recipient (to_user_id)
- Content (body)
- Subject (optional)
- Type (default: "message")
- Priority (default: "medium")
- Read status (read_at timestamp)
- Archive status (archived_at timestamp)

### Data Retention
- Messages are stored indefinitely unless deleted
- Deleted messages are permanently removed from the system

## Troubleshooting

### I can't see my messages
- Ensure you're logged in with the correct account
- Check that you're viewing the conversation with the correct user
- Refresh the page if messages don't appear

### I can't delete a message
- You can only delete messages you sent or received
- Make sure you're clicking the correct delete button
- If you're a Super Admin and still can't delete, contact system support

### The recipient dropdown is not working
- Try refreshing the page
- Ensure JavaScript is enabled in your browser
- Clear your browser cache and try again

## Support
For technical issues or questions about the messaging system, contact your system administrator or Super Admin.
