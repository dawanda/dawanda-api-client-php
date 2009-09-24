
load('spec/lib/jspec.js')
load('src/dawanda.js')

JSpec
.exec('spec/spec.core.js')
.run({ formatter : JSpec.formatters.Terminal })
.report()