
def JSONconverterDictionnary(self, data):
    """Converts a JSON file to a dictionary and returns it"""
    import json
    with open(data, 'r') as f:
        data = json.load(f)
    return data
    
    
if __name__ == '__main__':
    import sys
    print(JSONconverterDictionnary(sys.argv[1]))


    