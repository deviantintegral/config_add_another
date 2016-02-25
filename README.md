Configuration Add Another Example
=================================

An example Drupal 8 module showing:

* How to use sequences to store mappings of configuration (an array of
  configuration subkeys)
* How to use #ajax in forms to add additional items to the array configuration

Much of this was generated using Drupal Console's generate configuration entity
command. Nearly everything important to the example lives in the
DrupalVersionForm class, and in the drupal_version config schema file. See the
config/install directory for an example of how sequences of mappings export.

The DrupalVersion configuration entity is a simple class where each instance
is a Drupal major version, such as '8'. The 'releases' array in the class
contains the minor and patch versions, along with a boolean to note if the
release fixed security issues. A more complete implementation would also have
a 'Release' class instead of just relying on array structures to validate and
create each release item.

A more complex method of managing related configuration like this would be to
make each 'Release' it's own configuration entity. In that case, each
DrupalVersion configuration would have to have a [dependency](https://www.drupal.org/node/2235409)
on each Release configuration item, along with a custom UI for selecting the
related configuration. Config dependencies are somewhat analogous to entity
references, but are generally managed from code instead of the UI. In most cases
a simple sequence is enough to store related data, so consider it before
creating configuration dependencies. Save configuration dependencies for when
configuration is related, but both sets of configuration entities can live on
their own as well.
