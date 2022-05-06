
from random import randrange

# static game data - doesn't change (hence immutable tuple data type)
WIN_SET = (
    (0, 1, 2), (3, 4, 5), (6, 7, 8), (0, 3, 6),
    (1, 4, 7), (2, 5, 8), (0, 4, 8), (2, 4, 6)
)

# global variables for game data
board = [' '] * 9
current_player = ''  # 'x' or 'o' for first and second player

players = {
    'x': 'Badass AI',
    'o': 'Super AI',  # final comma is optional, but doesn't hurt
}

winner = None
move = None

# "horizontal rule", printed as a separator ...
HR = '-' * 40


#==============================================================================
# Game model functions

def check_move():
    
    global move
    try:
        move = int(move)
        if board[move] == ' ':
            return True
        else:
            print('>> Sorry - that position is already taken!')
            return False
    except:  # a "bare" except is bad practice, but simple and works still
        print('>> %s is not a valid position! Must be int between 0 and 8.' % move)
        return False


def check_for_result():
   
    for row in WIN_SET:
        if board[row[0]] == board[row[1]] == board[row[2]] != ' ':
            return board[row[0]]  # return an 'x' or 'o' to indicate winner

    if ' ' not in board:
        return 'tie'

    return None


#==============================================================================
# agent (human or AI) functions


def get_badassai_move():
    '''Get a Bad Ass AI input'''
    return randrange(9)


def get_ai_move():
    '''Get the AI's next move '''
    # A simple dumb random move - valid or NOT!
    # Note: It is the models responsibility to check for valid moves...
    return randrange(9)  # [0..8]


#==============================================================================
# Standard trinity of game loop methods (functions)

def process_input():
    '''Get the current players next move.'''
    # save the next move into a global variable
    global move
    if current_player == 'x':
        move = get_badassai_move()
    else:
        move = get_ai_move()


def update_model():
  
    global winner, current_player

    if check_move():
        # do the new move (which is stored in the global 'move' variable)
        board[move] = current_player
        # check board for winner (now that it's been updated)
        winner = check_for_result()
        # change the current player (regardless of the outcome)
        if current_player == 'x':
            current_player = 'o'
        else:
            current_player = 'x'
    else:
        print('Try again')


def render_board():
    '''Display the current game board to screen.'''

    print('    %s | %s | %s' % tuple(board[:3]))
    print('   -----------')
    print('    %s | %s | %s' % tuple(board[3:6]))
    print('   -----------')
    print('    %s | %s | %s' % tuple(board[6:]))

    # pretty print the current player name
    if winner is None:
        print('The current player is: %s' % players[current_player])


#==============================================================================


def show_human_help():
    '''Show the player help/instructions. '''
    tmp = '''
To make a move enter a number between 0 - 8 and press enter.
The number corresponds to a board position as illustrated:

    0 | 1 | 2
    ---------
    3 | 4 | 5
    ---------
    6 | 7 | 8
    '''
    print(tmp)
    print(HR)


#==============================================================================
# Separate the running of the game using a __name__ test. Allows the use of this
# file as an imported module
#==============================================================================


if __name__ == '__main__':
    # Welcome ...
    print('Welcome to the amazing+awesome tic-tac-toe!')
    show_human_help()

    # by default the human player starts. This could be random or a choice.
    current_player = 'x'

    # show the initial board and the current player's move
    render_board()

    # Standard game loop structure
    while winner is None:
        process_input()
        update_model()
        render_board()

    # Some pretty messages for the result
    print(HR)
    if winner == 'tie':
        print('TIE!')
    elif winner in players:
        print('%s is the WINNER!!!' % players[winner])
    print(HR)
    print('Game over. Goodbye')
