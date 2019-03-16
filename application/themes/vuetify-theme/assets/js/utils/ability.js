import { AbilityBuilder } from '@casl/ability'

const roleInSectionModule = (module, section) => {
  const permissions = ufhy.USER.permissions;
  return permissions[module][section];
};

export default AbilityBuilder.define(can => {
  const permissions = ufhy.USER.permissions;

  for (let key in permissions)
  {
    if (permissions.hasOwnProperty(key)) {
      if (Array.isArray(permissions[key])) {
        permissions[key].forEach(role => {
          if (typeof role === "string") {
            can(role, key);
          }
        });
      }

      else if (typeof permissions[key] === "object") {
        for (let keySection in permissions[key]) {
          const roles = roleInSectionModule(key, keySection);
          if (Array.isArray(roles)) {
            roles.forEach(role => {
              if (typeof role === "string") {
                can(role, keySection);
              }
            });
          }
        }
      }

    }
  }
});