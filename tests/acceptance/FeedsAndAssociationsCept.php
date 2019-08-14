<?php

/*
Four Kinds of URIs


SCENARIO: A "remote-content-post" is in the database
Given a "remote-content-post" is the atomic unit of content on the internet
And it could be a anything that Parler want to track comments or data about. i.e. Youtube videos, WordPress posts, naked HTML posts, whatever.
And it is a single URL that points to a content item
And it may have an image associated with it, possibly video, or other meta data
And it can have "parler-comments-enabled"
Then it must have at least 1 Parler user associated with the URL



Auto Post Feeds

Auto post feeds are a value added service offered by Parler.
We can authorize feeds either on the app, the web, or via a WordPress plugin.
There are millions of potential feeds on the internet.
The “value added” proposition is that the user can “auto” post the feed to their Parler account.
authorized
An additional value-added prop is that remote WP site admins could auto post to their user’s Parler accounts.
In any case, some Parler account may be authorized to auto publish some feed.

A feed URI is essentially a list of URLs.

A feed is an RSS feed / Atom / Post by email / Parler WordPress plugin / or any system of organizing content on the internet into a list of URLs

Feeds may have a “remote-admin-contact" associated with the feed, in order for the feed to be published. This is a Parler user who is the owner of the feed. Proven by installing the WP plugin, or inserting a secret code in the content of the feed. Note that the owner of a feed may not be the site owner. There is no connection between the site owner, the person who installs the WP plugin, a RSS site feed owner, or the author of the feed – they may all be different people.

remote-admin-contact – The owner of a feed

remote-wp-admin – The admin of a WP site

parler-user – the parler user publishing a content post or a feed

authorized-parler-user

not-authorized-parler-user

The parler-admin-contact is the human being who has activated the feed. This is the remote WordPress site admin, or a content creator who has activated a feed via a Parler backend with a secret code.



Pointing to a Feed or Content Post

At some point, someone has to “point” to a URI in order to associate the content with the user. We already have a mechanism for this, the +P form in the app. When a user enters a URI in the app, that should kick off the process of sorting the content post / feed. Most users will be entering single content posts most of the time, but they may enter a feed URI.

“Pointing” is the action of someone, either a process or a human being, trying to associate content with a Parler user. The most common is a user entering a URL into the +P form.

Additionally, in the future the WP plugin should “point” to potential Parler associations. I.e. when a site owner activates the WP plugin, Parler may want to prompt each individual author, who may not even have Parler accounts, that they have authorized auto post capability. The process is:

Point

Authorized?

Publish



Authorized vs Non-Authorized Feeds

And Parler user can point to any particular feed URI that we support. It’s a separate question if they are authorized to publish the feed or not.

Feeds a Parler user points to, that they are not authorized to Publish to, should be tracked. If a user points to a feed they are not allowed to publish, this is an opportunity for marketing. In this case, Parler should prompt the user to take action to authorize the feed.

Feeds that a Parler user is authorized to post, but they haven’t posted yet: I.e. a parler-admin-contact / remote-site-admin has authorized the Parler user to publish a feed, but the user has not actually published the feed. In this case, Parler should prompt the user.

Typical use case:

an author on a remote site, which has activated the Parler plugin, but who has not published the feed



Users should be prompted to take whatever action they need to do to authorize the feed. I.e. Contact your site admin and install the plugin [if it’s a WP site], put a secret code in the content [if you are the author of the content]

feeds may be tracked even though no one is publishing the feed, as long as the feed has a parler-admin-contact OR a parler-user who is attempting to post the content [whether that user is authroized or not]







A URI may be a content post AND OR a list of content posts



URL of a non-content post

login forms, system menus, blank URLs etc.

error

TODO: Keep a rolling list of last 1000 errors, ping admin if URL appears 3 times on the list



Alias

301 redirect of


SCENARIO: A Parler user is associated with a "remote-feed-uri"
Given a Parler user is associated with a "remote-feed-uri"
And that URI is from a "parler-enabled" WordPress site
And that Parler user is "authorized" to auto post the feed
Then the posts in the "remote-feed-list" should be published to that user's Parler feed




SCENARIO: Parler stores data
Given there is data to be stored
When Parler stores data about a URI
Then that URI must be parsed and classified
And one of those classifications is a "parler-content-post"
And one of those classifications is a "remote-content-post-url"
And one of those classifications is a "remote-feed-uri"
	And a feed uri is a list of content posts
		And one of these is a "feed" - a feed can be RSS, Atom, post by email etc. Any technology
		And one of these is a WordPress site
		And one of these is a "parler-enabled" WordPress site - has the Parler WP plugin turned on
And one of these is a "301" redirect
And one of those classifications is an "alias"
An alias is a URI that points to a URL
	And one of these is a WordPress "remote-post-id"
	And a URL that is not an alias or a 301 redirect is a "canonical-url"
	And a canonical URL cannot be an alias
	And a canonical URL can point to both a "remote-content-post-url" post and a "remote-feed-uri"
And one of those classifications is "error"

SCENARIO: A URI can point to a "remote-content-post-url" and a "remote-feed-uri"
Given a WordPress site exists or a any site with a "main-site-feed"
And a Parler user points to a "main site URL"
Then that URI can be both a "parler-content-post-url" and a "remote-feed-uri" and an "alias" of the other thing
And that Parler user is associated with the URI by the "associated" tag
And the Parler user may or may not be also associated with the URI by the "authorized" tag


SCENARIO: Parler outputs content posts in two ways
One of these ways is "full-post-output"
And one of these ways is "echo-post"

SCENARIO: Parler outputs a "full-post-output"
Given the Parler user is "associated" and "authroized" with content
When the Parler user is pointed at the post
Then the remote content post appears as a "full-post-output" in their Parler feed

SCENARIO: Parler outputs a "echo-post"
Given a Parler user enters a "remote-content-post-url" into the +P
And that user is not authorized to publish the content
Then that content is outputted as an "echo-post" on the user's feedi8kj ``

SCENARIO: A Parler user points to an unauthorized feed URI
Given a Parler user is not authroized to Publish a feed
When the Parler user point to the URI feed
A notification process ensues
And the feed is "associated" with the user






SCENARIO: A feed exists, someone points to it
Given a feed exists on the internet
When someone points to it
Then a record is made in the "feeds" table

SCENARIO: A feed exists in the "feeds" table
Given there is a feed item in the database
Then Parler should track the owner of the feed
And this person should be designated "feed-contact-admin"
And the "feed-contact-admin" is the human person in "control" of the feed

SCENARIO: A "feed-contact-admin" exists for a feed that is a main site feed on a WP site
Given there is a feed item in the DB
The fca is the "remote-site-admin" of the WP site

SCENARIO: A "feed-contact-admin" exists for a feed that is an author feed on a WP site
Given there is a feed item in the DB
The fca is the author of the content

SCENARIO: A feed exists that is an RSS feed
Given there is an RSS feed in the feeds tables
Then the fca is the Parler user who requested the secret authorization code [and installed it]



SCENARIO: A Parler user enters a new feed URI in the +P
Given there is a feed on the internet
When a Parler-user enters the feed URI into the +P
Then the user becomes associated with the feed
And the user is not authorized with the feed
And a record is made in the feeds table

SCENARIO: A URI is entered into the +P form that already exists and he is authorized to auto-publish
When a user has entered a URI
Then URI must be parsed
And it must be determined what kind of URI it is
And the URI is a feed
And the Parler-user is authorized to published the feed



Is it in the DB already?
NOT MUTIALLY EXCLUSIVE:
	content
	feed
WordPress site
WordPress “parler-enabled” site
Other feed – authorized
Other feed – not authorized
not content – a login form, table of contents etc.
error
Is it an alias?



SCENARIO: A URI is parsed that is in the database

Given a URI is entered that is in the db already
When a user enters the same URI into the +P
And it is a content post
Then "echo" the post









Then “content posts” “aliases” and “feeds” need to be distinguished




Is it already in the database?




There is a universe of URIs

Some of them are feed URLs

Some of them are content URLs

Some of them are content URL aliases

Some of them are content URL aliases of URL already in the database

If no Parler user is pointing to the URI, Parler should delete the URI from Parler’s DB So every content post must have a Parler user

http(s)://example.com/wp-json/wp/v2/posts




Is it a content post?

Some URLs are not content posts

Some URLs are feed posts

Some URLs are main site indexes

Some URLs are aliases of other content posts

Some URLs are 301 re-directs of other content posts

On WordPress sites, all content posts have remotePostIDs

The main site URL may be a content post

The main site URL may not be a content post



Content posts MAY have an alias

All posts of the post type “post” on a remote WordPress site can be found in this RSS feed:

http(s)://example.com/wp-json/wp/v2/posts

Is it a 301 redirect of a post in the database?




All post of the “post type” “pages” on a remote WordPress site can be found here:

http(s)://example.com/wp-json/wp/v2/pages




Usually, pages are not something Parler is interested in

However! A  user may enter a URL into +P that is of interest, and is a content post

http(s)://example.com/wp-json/wp/v2/pages




Is it a feed? A feed is a URI that represent a list of other posts, from any source.

- An RSS feed on a non parlor-enabled site

- A list of post from another technology that Parler supports, Atom, post by email, etc.?

- A URL from a WordPress parlor-enabled site?

- Parler should track all feeds a user is trying to auto-publish

- Parler should publish the combined list of posts that the user is trying to post, joined with the combined list of posts the user is authorized to post




If it is a feed:

- Is the Parler user authorized to publish the feed?

- Does the Parler user have a value added capability to publish the feed? Perhaps biz has sold them this ability.

- The Parler user can authorize a feed via a secret code in the post content of any post in the feed

- The WordPress parler-enabled site reports the Parler user is authorized to publish the content

- On WP sites, there is a list of posts that may be published at all, by someone:

https://gitlab.parler.com/frontend/parler-wordpress-php/wikis/API-Route:/parler/published-list




- On WP parler-enabled sites, there is a list of posts that a particular email may publish:

Remote WordPress site API Route: https://example.com/wp-json/parler/fetch-all-emails

Remote WordPress site API Route: https://example.com/wp-json/parler/fetch-by-email




- Any RSS feed, on a WP enabled site or not, would be added to the “list of sites the user is trying to publish”

- This list can be compared to the posts the user is “authorized” to publish




Given WordPress authors may have content feeds on many WordPress sites and many other feeds:

- Some of the sites authors feeds may have the plugin, some may not

- Either way, the user is either authorized - or not [via Parler website, via secret code, via plugin]

- SOME Parler user must be trying to publish a feed, or the record should be deleted

- It is not a requirement that a Parler user be authorized to publish a feed for Parler to keep a record

- the user may have entered a feed, and plans on subsequently turning on the plugin

- It is useful for marketing to have a list of feeds users are trying, but failing to publish




Given users can enter as many feeds as they want.

And users must be authorized to automatically publish a feed

Users should be able to see a list of feeds they have entered

- They should see the feeds they are authorized, and the feeds they have entered that they are not authorized to publish

- They should see what they have to do to publish the feed automatically

- They should be prompted that they have from parlor-enabled WordPress sites they have not authorized




Users should be able to remove feeds from their feed list




SCENARIO: a user enters a feed they are not authorized to publish

If non WordPress site

- forward to marketing

- ask user to tell remote WordPress admin to enable Wordpress plugin



If WordPress site

- ask user to tell WordPress admin to authorize him on the remote WordPress site

- prompt remote WordPress admin that Parler user has requested permission to publish URL

 
 

 
 

 
 

 
 

 
 

 
 

 
 

 