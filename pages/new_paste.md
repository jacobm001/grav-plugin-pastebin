---
title: New Paste

form:
  name: pastebin-new
  method: post

  fields:
    - name: title
      type: text
      id: title
      autofocus: true
      validate:
        required: true
    - name: author
      type: text
      id: author
      default: anonymous
    - name: lang
      type: text
      id: lang
      default: txt
    - name: raw
      type: textarea
      id: raw
      rows: 25
      validate:
          required: true
---

# Hello, World