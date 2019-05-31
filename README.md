# Randown

## Basic concepts

### Objects

Everything is an `Object`s in a _Randown_ document. An `Object` can be a _literal_
definition, it can be the result of a function call, or can be accessed through a
_reference_.

### References

A _reference_ is a _variable_ or a _constant_ (the difference is that the latter cannot be 
overridden) and they work exactly like they do in other programming languages.
Essentially, a textual identifier is associated to an `Object`, then that identifier can
be used to retrieve that `Object` at any time without having to recreate it. 

    $a reference can be any sequence of characters surrounded by dollar symbols$
    
The _normalized reference label_ is the same sequence of characters, but with any
subsequence of whitespace (`\t\r\n\f`) replaced by a single `\n` character, and with any
leading and trailing sequence of whitespace removed. For example the following references
are all identical:

    $     this   
          is a  
        reference   $
    
    $this is a reference$
    
    $     this     is     a     reference     $
    
### Callables invocation

Certain `Object`s can be invoked; invoking an `Object` produces a different `Object`; the
syntax is the following:

    $my callable$ &{arg 1 | arg 2 | arg 3 } function call with 3 args
    $my callable$ &{}                       function call w/o args

Similarly, `Object`s also have _methods_. The syntax is similar to the one of
`Callable`s.
    
    $ my object $& method name {arg 1 | arg 2 | arg 3 } method call with 3 args
    $ my object $& method name {}                       method call w/o args

_Callables_ take arguments, which are separated by the `|` character.

### Text

`Text` is a sequence of characters that stands in between other entities or special
characters and separators. _Randown_ is a _text-oriented_ language and unquoted strings
are one of its most important features. In the following example, ` foo ` is a `Text`
object, inclusive of the space characters, surrounded by two references:

    $reference 1$ foo $reference 2$
    

