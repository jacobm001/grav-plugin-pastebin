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
      default: Anonymous
    - name: lang
      type: select
      id: lang
      options:
        txt: Plain Text
        markup: Markup
        css: CSS
        clike: C-like
        javascript: JavaScript
        abap: ABAP
        actionscript: ActionScript
        ada: Ada
        apacheconf: Apache Configuration
        apl: APL
        applescript: AppleScript
        asciidoc: AsciiDoc
        aspnet: ASP.NET (C#)
        autoit: AutoIt
        autohotkey: AutoHotkey
        bash: Bash
        basic: BASIC
        batch: Batch
        bison: Bison
        brainfuck: Brainfuck
        bro: Bro
        c: C
        csharp: C#
        cpp: C++
        coffeescript: CoffeeScript
        crystal: Crystal
        css-extras: CSS Extras
        d: D
        dart: Dart
        django: Django/Jinja2
        diff: Diff
        docker: Docker
        eiffel: Eiffel
        elixir: Elixir
        erlang: Erlang
        fsharp: F#
        fortran: Fortran
        gherkin: Gherkin
        git: Git
        glsl: GLSL
        go: Go
        graphql: GraphQL
        groovy: Groovy
        haml: Haml
        handlebars: Handlebars
        haskell: Haskell
        haxe: Haxe
        http: HTTP
        icon: Icon
        inform7: Inform 7
        ini: Ini
        j: J
        jade: Jade
        java: Java
        jolie: Jolie
        json: JSON
        julia: Julia
        keyman: Keyman
        kotlin: Kotlin
        latex: LaTeX
        less: Less
        livescript: LiveScript
        lolcode: LOLCODE
        lua: Lua
        makefile: Makefile
        markdown: Markdown
        matlab: MATLAB
        mel: MEL
        mizar: Mizar
        monkey: Monkey
        nasm: NASM
        nginx: nginx
        nim: Nim
        nix: Nix
        nsis: NSIS
        objectivec: Objective-C
        ocaml: OCaml
        oz: Oz
        parigp: PARI/GP
        parser: Parser
        pascal: Pascal
        perl: Perl
        php: PHP
        php-extras: PHP Extras
        powershell: PowerShell
        processing: Processing
        prolog: Prolog
        properties: .properties
        protobuf: Protocol Buffers
        puppet: Puppet
        pure: Pure
        python: Python
        q: Q
        qore: Qore
        r: R
        jsx: React JSX
        reason: Reason
        rest: reST (reStructuredText)
        rip: Rip
        roboconf: Roboconf
        ruby: Ruby
        rust: Rust
        sas: SAS
        sass: Sass (Sass)
        scss: Sass (Scss)
        scala: Scala
        scheme: Scheme
        smalltalk: Smalltalk
        smarty: Smarty
        sql: SQL
        stylus: Stylus
        swift: Swift
        tcl: Tcl
        textile: Textile
        twig: Twig
        typescript: TypeScript
        vbnet: VB.Net
        verilog: Verilog
        vhdl: VHDL
        vim: vim
        wiki: Wiki markup
        xojo: Xojo (REALbasic)
        yaml: YAML
    - name: raw
      type: textarea
      id: raw
      rows: 25
      validate:
          required: true
---

# Hello, World