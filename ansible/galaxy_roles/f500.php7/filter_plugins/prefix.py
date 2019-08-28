from ansible import errors
from ansible.module_utils._text import to_text

def prefix(value, prefix):
    '''
    Prefix each item in a list with a prefix
    '''

    try:
        return prefix + value
    except TypeError:
        raise errors.AnsibleFilterError('This filter only works on a string, offending value: ' + to_text(value))


class FilterModule(object):
    '''
    Custom jinja2 filter to prefix a string
    '''

    def filters(self):
        return {
            'prefix': prefix,
        }
